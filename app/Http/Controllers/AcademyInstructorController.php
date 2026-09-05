<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * A12 - an instructor sees only their own students/commission. Read-only,
 * no new calculation - just surfaces what A6 already computes.
 */
class AcademyInstructorController extends Controller
{
    public function index()
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyInstructor() || isAdministrator()), 403);

        $enrollments = Auth::user()->instructedAcademyEnrollments()->with('user', 'track', 'feeInvoices')->get();
        $totalCommission = $enrollments->flatMap->feeInvoices->where('instructor_commission_credited', true)->sum('instructor_commission_amount');

        return view('academy.instructor', compact('enrollments', 'totalCommission'));
    }
}
