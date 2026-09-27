<?php

namespace App\Http\Controllers;

use App\Models\AcademyAiReview;
use App\Models\AcademyFeeInvoice;
use App\Services\GeminiReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

/**
 * A2 - what a logged-in student sees. Role-gated in the route definition
 * (see routes/web.php), not here.
 */
class AcademyDashboardController extends Controller
{
    public function index()
    {
        $enrollment = Auth::user()->academyEnrollments()->active()->with('track')->latest()->first();

        if (!$enrollment) {
            abort(404, 'No active Academy enrollment found for your account.');
        }

        $stages = $enrollment->track->stages();
        $currentStage = $stages[$enrollment->current_stage] ?? null;
        $progress = $enrollment->progress[$enrollment->current_stage] ?? ['skills' => [], 'projects' => []];
        $allProgress = $enrollment->progress ?? [];
        // Every unpaid invoice, not just the latest month - otherwise a
        // student with an overdue PAST month's fee would never see it on
        // their own dashboard at all, only whatever's most recent.
        $unpaidInvoices = $enrollment->feeInvoices()->where('status', '!=', AcademyFeeInvoice::STATUS_PAID)->oldest('month')->get();
        $invoice = $unpaidInvoices->first() ?? $enrollment->feeInvoices()->latest('month')->first();
        $reviews = $enrollment->aiReviews()->latest()->get();
        $certificates = Auth::user()->academyCertificates()->latest('issued_at')->get();

        // Each stage's skill list is shown as a suggested week-by-week plan
        // rather than one long flat list - makes a multi-week stage feel
        // like a set of daily/weekly sub-tasks instead of one big checklist.
        // This is a mechanical split of the existing skills array across
        // the stage's stated duration, not a separately-authored day-by-day
        // curriculum - staff can still edit the underlying skills list via
        // Voyager (admin/academy-tracks) same as before.
        $weeklyPlans = collect($stages)->map(
            fn (array $stage) => $this->groupSkillsByWeek($stage['skills'] ?? [], $this->parseWeekCount($stage['duration'] ?? ''))
        )->all();

        return view('academy.dashboard', compact('enrollment', 'stages', 'currentStage', 'progress', 'allProgress', 'weeklyPlans', 'invoice', 'unpaidInvoices', 'reviews', 'certificates'));
    }

    protected function parseWeekCount(string $duration): int
    {
        if (preg_match('/(\d+)(?:-(\d+))?\s*week/i', $duration, $matches)) {
            $low = (int) $matches[1];
            $high = isset($matches[2]) ? (int) $matches[2] : $low;

            return max(1, (int) round(($low + $high) / 2));
        }

        return 1;
    }

    /**
     * @return array<string, array<int, string>> "Week N" => [skillIndex => skillText]
     */
    protected function groupSkillsByWeek(array $skills, int $weeks): array
    {
        if (empty($skills)) {
            return [];
        }

        $perWeek = max(1, (int) ceil(count($skills) / $weeks));
        $groups = [];

        foreach (array_values($skills) as $index => $skill) {
            $week = intdiv($index, $perWeek) + 1;
            $groups["Week {$week}"][$index] = $skill;
        }

        return $groups;
    }

    /**
     * Toggle a skill checkbox for the student's current stage - patches the
     * progress JSON in place, per A2's plan. No page reload.
     */
    public function toggleSkill(Request $request)
    {
        $data = $request->validate(['skill_key' => 'required|string']);
        $enrollment = Auth::user()->academyEnrollments()->active()->firstOrFail();

        $progress = $enrollment->progress ?? [];
        $stageKey = (string) $enrollment->current_stage;
        $current = data_get($progress, "{$stageKey}.skills.{$data['skill_key']}", false);
        data_set($progress, "{$stageKey}.skills.{$data['skill_key']}", !$current);

        $enrollment->update(['progress' => $progress]);

        return response()->json(['ok' => true, 'checked' => !$current, 'progress_percent' => $enrollment->fresh()->progressPercent()]);
    }

    public function submitProject(Request $request)
    {
        $data = $request->validate([
            'project_title' => 'required|string|max:255',
            'submission_link' => 'required|url|max:500',
            'notes' => 'nullable|string|max:2000',
        ]);

        $enrollment = Auth::user()->academyEnrollments()->active()->firstOrFail();
        $stage = $enrollment->track->stages()[$enrollment->current_stage] ?? ['title' => 'Unknown Stage', 'skills' => []];

        $review = AcademyAiReview::create([
            'enrollment_id' => $enrollment->id,
            'stage_index' => $enrollment->current_stage,
            'project_title' => $data['project_title'],
            'submission_link' => $data['submission_link'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Runs inline (sync) per this app's QUEUE_CONNECTION - written so this
        // one line is all that changes to make it async later.
        $result = app(GeminiReviewService::class)->review($stage['title'], $stage['skills'], $data['submission_link'], $data['notes'] ?? null);

        $review->update([
            'ai_score' => $result['score'],
            'ai_verdict' => $result['verdict'],
            'ai_feedback' => $result['feedback'],
        ]);

        $this->announceSubmissionToSlack(Auth::user()->name, $stage['title'], $review);

        return redirect()->route('academy.dashboard')->with('status', 'Submission received - your reviewer will confirm it soon.');
    }

    /**
     * Student attaches a screenshot (bank transfer / JazzCash / EasyPaisa
     * etc.) for a pending invoice. Doesn't mark it paid - that's still the
     * accountant's call (markPaid(), AcademyFeeController) - this just gives
     * them a screenshot to check instead of having to chase it down.
     */
    public function submitPaymentProof(Request $request, AcademyFeeInvoice $invoice)
    {
        abort_unless(
            Auth::user()->academyEnrollments()->pluck('id')->contains($invoice->enrollment_id),
            403,
            'This invoice does not belong to you.'
        );

        if ($invoice->status === AcademyFeeInvoice::STATUS_PAID) {
            return back()->with('status', 'This invoice is already marked paid.');
        }

        $data = $request->validate(['proof' => 'required|image|max:4096']);

        $invoice->update([
            'payment_proof_path' => $request->file('proof')->store('academy/payment-proofs', 'public'),
            'payment_proof_submitted_at' => now(),
        ]);

        return back()->with('status', 'Payment proof submitted - your accountant will confirm it soon.');
    }

    /**
     * Lets the reviewer team see new submissions land in real time instead
     * of having to poll the review queue - same Academy Slack webhook
     * check-in/check-out and certificate issuance already post to. Includes
     * the AI's own take so a reviewer can triage without opening the queue
     * first.
     */
    protected function announceSubmissionToSlack(string $studentName, string $stageTitle, AcademyAiReview $review): void
    {
        $webhookUrl = setting('academy.slack_webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $text = "📥 *{$studentName}* submitted a project for review!\n"
            . "*Stage:* {$stageTitle}\n"
            . "*Project:* {$review->project_title}\n"
            . "*Link:* {$review->submission_link}\n"
            . '*AI Verdict:* ' . ucfirst($review->ai_verdict ?? 'pending') . " ({$review->ai_score}/100)";

        if ($review->notes) {
            $text .= "\n*Student's Notes:* {$review->notes}";
        }

        $blocks = [
            [
                'type' => 'section',
                'text' => ['type' => 'mrkdwn', 'text' => $text],
            ],
        ];

        try {
            SendToSlackChannelJob::dispatchSync($webhookUrl, null, $blocks);
        } catch (\Throwable $e) {
            Log::error('Academy submission Slack announcement failed', ['error' => $e->getMessage()]);
        }
    }
}
