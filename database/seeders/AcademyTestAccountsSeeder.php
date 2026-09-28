<?php

namespace Database\Seeders;

use App\Models\AcademyStaffRole;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\Setting;

/**
 * Dedicated, restricted, consequence-free test accounts for exercising
 * every Academy role end-to-end - none of these touch a real staff
 * member's access. All passwords: Test@12345. Idempotent.
 *
 * Not meant for production - this is purely for local/dev testing, same
 * spirit as AcademyAccountantMarketingSeeder's test accounts.
 */
class AcademyTestAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $itAcademyStudentRoleId = (int) Setting::where('key', 'academy.it_academy_student_role_id')->value('value');

        $accounts = [
            ['email' => 'test.student@webpenter.test', 'name' => 'Test Student', 'role_id' => $itAcademyStudentRoleId, 'capability' => null, 'enroll' => true],
            ['email' => 'test.instructor@webpenter.test', 'name' => 'Test Instructor', 'role_id' => Role::where('name', 'academy-instructor')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_INSTRUCTOR, 'enroll' => false],
            ['email' => 'test.reviewer@webpenter.test', 'name' => 'Test Reviewer', 'role_id' => Role::where('name', 'academy-reviewer')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_REVIEWER, 'enroll' => false],
            ['email' => 'test.hr@webpenter.test', 'name' => 'Test HR', 'role_id' => Role::where('name', 'HR')->value('id'), 'capability' => null, 'enroll' => false],
            ['email' => 'test.accountant@webpenter.test', 'name' => 'Test Accountant', 'role_id' => Role::where('name', 'academy-accountant')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_ACCOUNTANT, 'enroll' => false],
            ['email' => 'test.marketing@webpenter.test', 'name' => 'Test Marketing', 'role_id' => Role::where('name', 'academy-marketing')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_MARKETING, 'enroll' => false],
            ['email' => 'test.printer@webpenter.test', 'name' => 'Test Printer', 'role_id' => Role::where('name', 'academy-printer')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_PRINTER, 'enroll' => false],
            ['email' => 'test.cardmanager@webpenter.test', 'name' => 'Test Card Manager', 'role_id' => Role::where('name', 'academy-card-manager')->value('id'), 'capability' => AcademyStaffRole::CAPABILITY_CARD_MANAGER, 'enroll' => false],
        ];

        foreach ($accounts as $acc) {
            // withTrashed(): User uses SoftDeletes, so a test account
            // deleted via Voyager (soft-delete by default) would otherwise
            // be invisible to a plain where()->first() while still holding
            // its email in the unique index - the next create() then fails
            // with a duplicate-key error instead of just reusing the row.
            $user = User::withTrashed()->where('email', $acc['email'])->first();

            if (!$user) {
                $user = new User(['name' => $acc['name'], 'email' => $acc['email'], 'password' => Hash::make('Test@12345')]);
            } elseif ($user->trashed()) {
                $user->restore();
            }

            $user->role_id = $acc['role_id'];
            $user->password = Hash::make('Test@12345');
            $user->save();

            if ($acc['capability']) {
                AcademyStaffRole::firstOrCreate(['user_id' => $user->id, 'capability' => $acc['capability']]);
            }

            if ($acc['enroll'] && !$user->academyEnrollments()->exists()) {
                $track = \App\Models\AcademyTrack::where('is_open_for_enrollment', true)->first();
                if ($track) {
                    $enrollment = DeveloperAcademyEnrollment::create([
                        'user_id' => $user->id,
                        'track_id' => $track->id,
                        'instructor_id' => $track->default_instructor_id,
                        'status' => DeveloperAcademyEnrollment::STATUS_ACTIVE,
                    ]);
                    \App\Models\AcademyFeeInvoice::create([
                        'enrollment_id' => $enrollment->id,
                        'month' => now()->startOfMonth(),
                        'registration_fee_amount' => $track->registration_fee_enabled ? $track->registration_fee_amount : 0,
                        'monthly_fee_amount' => $track->monthly_fee_amount,
                        'total_amount' => ($track->registration_fee_enabled ? $track->registration_fee_amount : 0) + $track->monthly_fee_amount,
                    ]);
                }
            }

            $this->command?->info("{$acc['email']}: ready.");
        }

        // So the instructor dashboard has something real to show - assign
        // test.instructor as test.student's instructor.
        if (($testInstructor = User::where('email', 'test.instructor@webpenter.test')->first())
            && ($testStudent = User::where('email', 'test.student@webpenter.test')->first())
        ) {
            $testStudent->academyEnrollments()->update(['instructor_id' => $testInstructor->id]);
            $this->command?->info('Assigned test.instructor as test.student\'s instructor.');
        }
    }
}
