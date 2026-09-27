<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyFeeInvoice;
use App\Models\AcademyTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * A6 - fee admin screen. Academy fees are the Academy Accountant
 * capability's job (additive - academy_staff_roles, same as
 * instructor/reviewer - a person can hold this alongside their existing
 * primary role, e.g. HR staff who also handles Academy fees), or
 * Administrator. Explicitly NOT plain HR by itself, per instruction.
 * markPaid() is the ONLY place instructor commission gets credited.
 */
class AcademyFeeController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyAccountant() || isAdministrator()), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $filters = $request->only(['status', 'month', 'track_id', 'needs_attention', 'student']);

        $invoices = AcademyFeeInvoice::with('enrollment.user', 'enrollment.track', 'enrollment.instructor')
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['month'] ?? null, fn ($q, $month) => $q->whereDate('month', $month))
            ->when($filters['track_id'] ?? null, fn ($q, $trackId) => $q->whereHas('enrollment', fn ($eq) => $eq->where('track_id', $trackId)))
            ->when($filters['needs_attention'] ?? null, fn ($q) => $q->whereNotNull('payment_proof_submitted_at')->where('status', '!=', AcademyFeeInvoice::STATUS_PAID))
            ->when($filters['student'] ?? null, fn ($q, $name) => $q->whereHas('enrollment.user', fn ($uq) => $uq->where('name', 'like', "%{$name}%")))
            ->latest('month')
            ->paginate(20)
            ->withQueryString();

        $tracks = AcademyTrack::orderBy('name')->get(['id', 'name']);

        return view('academy.fee-admin', compact('invoices', 'tracks', 'filters'));
    }

    public function markPaid(AcademyFeeInvoice $invoice)
    {
        $this->authorizeStaff();
        $invoice->markPaid(Auth::user());

        return back()->with('status', "Marked paid. Instructor commission: Rs. " . number_format($invoice->instructor_commission_amount ?? 0, 2));
    }
}
