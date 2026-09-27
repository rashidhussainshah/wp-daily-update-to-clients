<?php

namespace App\Http\Controllers;

use App\Models\AcademyFeeInvoice;
use App\Models\DeveloperAcademyEnrollment;
use Illuminate\Support\Facades\Auth;

/**
 * The accountant's actual day-to-day screen - a small, student-dashboard-
 * style page (cards, not a giant table) focused on what needs a decision
 * right now, instead of the full Voyager fee-admin table (still there,
 * linked from here, for anyone who wants to browse/filter everything).
 *
 * Same auth pattern as the student/instructor dashboards - plain 'auth'
 * middleware, role-checked here rather than a separate gate.
 */
class AcademyAccountantDashboardController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyAccountant() || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $needsReview = AcademyFeeInvoice::whereNotNull('payment_proof_submitted_at')
            ->where('status', '!=', AcademyFeeInvoice::STATUS_PAID)
            ->with('enrollment.user', 'enrollment.track')
            ->latest('payment_proof_submitted_at')
            ->get();

        $pendingNoProof = AcademyFeeInvoice::whereNull('payment_proof_submitted_at')
            ->where('status', '!=', AcademyFeeInvoice::STATUS_PAID)
            ->count();

        $collectedThisMonth = AcademyFeeInvoice::where('status', AcademyFeeInvoice::STATUS_PAID)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_amount');

        return view('academy.accountant-dashboard', compact('needsReview', 'pendingNoProof', 'collectedThisMonth'));
    }

    public function markPaid(AcademyFeeInvoice $invoice)
    {
        $this->authorizeStaff();
        $invoice->markPaid(Auth::user());

        return back()->with('status', 'Marked paid. Instructor commission: Rs. ' . number_format($invoice->instructor_commission_amount ?? 0, 2));
    }

    public function students()
    {
        $this->authorizeStaff();

        $enrollments = DeveloperAcademyEnrollment::active()
            ->with('user', 'track')
            ->get();

        return view('academy.accountant-students', compact('enrollments'));
    }

    /**
     * Soft-delete only - the enrollment (and everything tied to it -
     * invoices, reviews) simply stops appearing anywhere active. Nothing
     * is destroyed; restorable via php artisan tinker
     * (DeveloperAcademyEnrollment::withTrashed()->find($id)->restore())
     * if removed by mistake. Does NOT touch the student's User account.
     */
    public function removeStudent(DeveloperAcademyEnrollment $enrollment)
    {
        $this->authorizeStaff();
        $name = $enrollment->user->name;
        $enrollment->delete();

        return redirect()->route('academy.accountant.students')->with('status', "{$name}'s enrollment was removed (not permanently - it can be restored if needed).");
    }
}
