<?php

namespace App\Http\Controllers;

use App\Models\AcademyFeeInvoice;
use App\Models\AcademyTrack;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * A13 - public, no-login self-service registration. Fully automatic: no
 * approval step, instructor auto-assigned, voucher generated immediately.
 */
class AcademyRegistrationController extends Controller
{
    public function create()
    {
        $tracks = AcademyTrack::where('is_open_for_enrollment', true)->get();

        return view('academy.register', compact('tracks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'track_id' => [
                'required',
                Rule::exists('academy_tracks', 'id')->where('is_open_for_enrollment', true),
            ],
        ]);

        $track = AcademyTrack::findOrFail($data['track_id']);

        // role_id isn't mass-assignable on User (fillable is just
        // name/email/password) - set it explicitly, otherwise it's left null
        // and Voyager's own "assign default role" listener kicks in instead.
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(16)), // student sets/resets their own password via the normal flow
        ]);
        $user->role_id = setting('academy.it_academy_student_role_id');
        $user->save();

        $enrollment = DeveloperAcademyEnrollment::create([
            'user_id' => $user->id,
            'track_id' => $track->id,
            'instructor_id' => $track->default_instructor_id,
            'status' => DeveloperAcademyEnrollment::STATUS_ACTIVE,
        ]);

        $registrationFee = $track->registration_fee_enabled ? (float) $track->registration_fee_amount : 0;
        $monthlyFee = (float) $track->monthly_fee_amount;

        $invoice = AcademyFeeInvoice::create([
            'enrollment_id' => $enrollment->id,
            'month' => now()->startOfMonth(),
            'registration_fee_amount' => $registrationFee,
            'monthly_fee_amount' => $monthlyFee,
            'total_amount' => $registrationFee + $monthlyFee,
        ]);

        return redirect()
            ->route('academy.register.success', $enrollment->id)
            ->with('invoice_id', $invoice->id);
    }

    public function success(DeveloperAcademyEnrollment $enrollment)
    {
        $invoice = $enrollment->feeInvoices()->latest()->first();

        return view('academy.register-success', compact('enrollment', 'invoice'));
    }
}
