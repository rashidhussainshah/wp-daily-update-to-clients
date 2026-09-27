<?php

namespace App\Http\Controllers;

use App\Models\AcademyAiReview;
use Illuminate\Support\Facades\Auth;

/**
 * The reviewer's simple day-to-day screen - same data as
 * Voyager\AcademyReviewController::index(), presented as a student-
 * dashboard-style page instead of a Voyager table. Approve/Send Back
 * still POST to the existing academy.review.approve/send-back routes
 * (Voyager\AcademyReviewController) - no logic duplicated, just a
 * different front door onto the same actions.
 */
class AcademyReviewerDashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyReviewer() || isAdministrator()), 403);

        $query = AcademyAiReview::pending()->with('enrollment.user', 'enrollment.track')->latest();

        if (!isAdministrator()) {
            $query->whereHas('enrollment', fn ($q) => $q->where('instructor_id', Auth::id()));
        }

        $reviews = $query->get();

        return view('academy.reviewer-dashboard', compact('reviews'));
    }
}
