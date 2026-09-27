<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyAiReview;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * A3 (staff side) - the review queue. Approve is the single trigger: it
 * advances the stage, awards the badge, and (on the final stage) issues the
 * track certificate via CertificateService - same engine A9 reuses.
 *
 * Access: Academy Reviewer capability or Administrator - see
 * User::isAcademyReviewer()/isAdministrator(), assignment-scoped to a
 * reviewer's own enrollments unless they're an Administrator.
 */
class AcademyReviewController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyReviewer() || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $query = AcademyAiReview::pending()->with('enrollment.user', 'enrollment.track')->latest();

        if (!isAdministrator()) {
            // Assignment-scoped: a reviewer only sees submissions from
            // students they're actually assigned to via the enrollment's
            // instructor - refine to a dedicated reviewer assignment later
            // if reviewers and instructors need to differ per student.
            $query->whereHas('enrollment', fn ($q) => $q->where('instructor_id', Auth::id()));
        }

        $reviews = $query->get();

        return view('academy.review-queue', compact('reviews'));
    }

    public function approve(AcademyAiReview $review, CertificateService $certificates)
    {
        $this->authorizeStaff();
        $this->authorizeReview($review);

        $enrollment = $review->enrollment;
        $review->update([
            'reviewer_status' => AcademyAiReview::REVIEWER_STATUS_APPROVED,
            'reviewer_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $stages = $enrollment->track->stages();
        $badges = $enrollment->badges_earned ?? [];
        $badges[] = ['title' => $stages[$enrollment->current_stage]['title'] ?? 'Stage Complete', 'awarded_at' => now()->toDateString()];

        $isFinalStage = $enrollment->current_stage >= count($stages) - 1;

        $enrollment->update([
            'current_stage' => $isFinalStage ? $enrollment->current_stage : $enrollment->current_stage + 1,
            'badges_earned' => $badges,
            'status' => $isFinalStage ? \App\Models\DeveloperAcademyEnrollment::STATUS_COMPLETED : $enrollment->status,
        ]);

        if ($isFinalStage) {
            $certificates->issueTrackCertificate($enrollment);
        }

        return back()->with('status', 'Approved.' . ($isFinalStage ? ' Track certificate issued.' : ' Student advanced to the next stage.'));
    }

    public function sendBack(Request $request, AcademyAiReview $review)
    {
        $this->authorizeStaff();
        $this->authorizeReview($review);

        $review->update([
            'reviewer_status' => AcademyAiReview::REVIEWER_STATUS_SENT_BACK,
            'reviewer_id' => Auth::id(),
            'reviewer_notes' => $request->input('reviewer_notes'),
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Sent back to the student.');
    }

    protected function authorizeReview(AcademyAiReview $review): void
    {
        abort_unless(
            isAdministrator() || $review->enrollment->instructor_id === Auth::id(),
            403,
            'You are not the assigned reviewer for this student.'
        );
    }
}
