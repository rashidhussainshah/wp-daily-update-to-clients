<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyFeeInvoice;
use Illuminate\Support\Facades\Auth;

/**
 * A6 - fee admin screen. Access: whoever holds the HR role (reuses the
 * existing Voyager "HR" role - no new role needed) or Administrator.
 * markPaid() is the ONLY place instructor commission gets credited.
 */
class AcademyFeeController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (optional(Auth::user()->role)->name === 'HR' || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $invoices = AcademyFeeInvoice::with('enrollment.user', 'enrollment.track', 'enrollment.instructor')
            ->latest('month')->paginate(20);

        return view('academy.fee-admin', compact('invoices'));
    }

    public function markPaid(AcademyFeeInvoice $invoice)
    {
        $this->authorizeStaff();
        $invoice->markPaid(Auth::user());

        return back()->with('status', "Marked paid. Instructor commission: Rs. " . number_format($invoice->instructor_commission_amount ?? 0, 2));
    }
}
