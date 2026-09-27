<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyFeeInvoice;
use App\Models\AcademyInstructorManualPayment;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Administrator-only: reviewing and actually paying out instructor
 * commission, plus one-off advances/bonuses/adjustments not tied to a
 * specific student's fee. "Credited" (AcademyFeeController::markPaid) just
 * means the amount was calculated and attributed when the student's fee
 * was marked paid - it says nothing about whether the instructor was ever
 * actually paid that money. This is that second, separate step.
 */
class AcademyInstructorPayoutController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(isAdministrator(), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $instructorId = $request->query('instructor_id');

        $pending = AcademyFeeInvoice::pendingInstructorPayout()
            ->with('enrollment.user', 'enrollment.track', 'enrollment.instructor')
            ->when($instructorId, fn ($q) => $q->whereHas('enrollment', fn ($eq) => $eq->where('instructor_id', $instructorId)))
            ->latest('paid_at')
            ->get();

        $totalPending = $pending->sum('instructor_commission_amount');

        // Merge both payment sources (per-invoice commission + one-off
        // advance/bonus/adjustment) into one chronological history - not a
        // true paginated query since it spans two tables, but plenty for
        // this scale; most recent 50 of each is far more than a normal
        // review session needs.
        $commissionHistory = AcademyFeeInvoice::where('instructor_commission_credited', true)
            ->whereNotNull('instructor_paid_at')
            ->with('enrollment.user', 'enrollment.track', 'enrollment.instructor', 'instructorPaidBy')
            ->when($instructorId, fn ($q) => $q->whereHas('enrollment', fn ($eq) => $eq->where('instructor_id', $instructorId)))
            ->latest('instructor_paid_at')
            ->limit(50)
            ->get()
            ->map(fn ($invoice) => [
                'type' => 'Commission',
                'instructor' => $invoice->enrollment->instructor,
                'detail' => $invoice->enrollment->user->name . ' - ' . $invoice->month->format('F Y'),
                'amount' => $invoice->instructor_commission_amount,
                'paid_at' => $invoice->instructor_paid_at,
                'paid_by' => $invoice->instructorPaidBy,
                'proof_path' => $invoice->instructor_payout_proof_path,
            ]);

        $manualHistory = AcademyInstructorManualPayment::with('instructor', 'paidBy')
            ->when($instructorId, fn ($q) => $q->where('instructor_id', $instructorId))
            ->latest('paid_at')
            ->limit(50)
            ->get()
            ->map(fn ($payment) => [
                'type' => ucfirst($payment->type),
                'instructor' => $payment->instructor,
                'detail' => $payment->note ?: '-',
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at,
                'paid_by' => $payment->paidBy,
                'proof_path' => $payment->proof_path,
            ]);

        $history = $commissionHistory->concat($manualHistory)->sortByDesc('paid_at')->values();

        $instructors = User::academyInstructors()->orderBy('name')->get(['id', 'name']);

        return view('academy.instructor-payouts', compact('pending', 'history', 'totalPending', 'instructors', 'instructorId'));
    }

    public function markPaid(Request $request, AcademyFeeInvoice $invoice)
    {
        $this->authorizeStaff();

        $request->validate(['proof' => 'nullable|file|max:4096']);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('academy/instructor-payout-proofs', 'public');
        }

        $invoice->markInstructorPaid($request->user(), $proofPath);

        return back()->with('status', 'Marked as paid to ' . ($invoice->enrollment->instructor->name ?? 'instructor') . '.');
    }

    public function storeManualPayment(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:advance,bonus,adjustment',
            'note' => 'nullable|string|max:500',
            'proof' => 'nullable|file|max:4096',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('academy/instructor-payout-proofs', 'public');
        }

        AcademyInstructorManualPayment::create([
            'instructor_id' => $data['instructor_id'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'note' => $data['note'] ?? null,
            'proof_path' => $proofPath,
            'paid_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Payment recorded.');
    }
}
