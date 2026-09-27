<?php

namespace App\Http\Controllers;

use App\Models\DeveloperAcademyEnrollment;
use App\Models\Checkin;

/**
 * A14 - public, unguessable link (the token IS the auth - no login). Reuses
 * the same Checkin data as A10/A2, nothing new to calculate.
 */
class AcademyParentController extends Controller
{
    public function show(string $token)
    {
        $enrollment = DeveloperAcademyEnrollment::where('parent_view_token', $token)
            ->with('user', 'track')
            ->firstOrFail();

        $checkins = Checkin::where('developer_id', $enrollment->user_id)
            ->latest('checkin_at')->limit(14)->get();

        $certificates = $enrollment->user->academyCertificates ?? collect();
        $showFee = setting('academy.show_fee_to_parents', false);
        $invoice = $showFee ? $enrollment->feeInvoices()->latest('month')->first() : null;

        return view('academy.parent', compact('enrollment', 'checkins', 'certificates', 'showFee', 'invoice'));
    }
}
