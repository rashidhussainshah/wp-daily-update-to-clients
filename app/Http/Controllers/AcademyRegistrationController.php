<?php

namespace App\Http\Controllers;

use App\Models\AcademyFeeInvoice;
use App\Models\AcademyTrack;
use App\Models\CheckinConfiguration;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|max:4096',
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
            'password' => Hash::make($data['password']),
        ]);
        $user->role_id = setting('academy.it_academy_student_role_id');

        // Stored the same way Voyager stores every other avatar (a plain
        // relative path on the public disk) so it also shows correctly
        // anywhere Voyager already renders a user's avatar, not just on
        // the Academy ID card.
        if ($request->hasFile('photo')) {
            $user->avatar = $request->file('photo')->store('users', 'public');
        }

        $user->save();

        $enrollment = DeveloperAcademyEnrollment::create([
            'user_id' => $user->id,
            'track_id' => $track->id,
            'instructor_id' => $track->default_instructor_id,
            'status' => DeveloperAcademyEnrollment::STATUS_ACTIVE,
        ]);

        // Check-in/check-out (CheckinController) requires a
        // CheckinConfiguration row to exist for the user - it's what
        // supplies the Slack webhook and designation shown in the
        // notification. Students don't use Clockify, so this is the only
        // setup they need; the shared Academy webhook lives in the
        // academy.slack_webhook_url setting, editable in Voyager.
        CheckinConfiguration::firstOrCreate(
            ['developer_id' => $user->id],
            [
                'slack_webhook_url' => setting('academy.slack_webhook_url'),
                'designation' => $track->designation ?? $track->name,
            ]
        );

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
