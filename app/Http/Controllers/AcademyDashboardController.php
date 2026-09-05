<?php

namespace App\Http\Controllers;

use App\Models\AcademyAiReview;
use App\Services\GeminiReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $invoice = $enrollment->feeInvoices()->latest('month')->first();
        $reviews = $enrollment->aiReviews()->latest()->get();

        return view('academy.dashboard', compact('enrollment', 'stages', 'currentStage', 'progress', 'invoice', 'reviews'));
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

        return redirect()->route('academy.dashboard')->with('status', 'Submission received - your reviewer will confirm it soon.');
    }
}
