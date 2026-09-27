<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use TCG\Voyager\Models\Role;

/**
 * Dev/local-only test account for the hr-document-manager role, password
 * Test@12345, same convention as AcademyTestAccountsSeeder. Deliberately
 * NOT part of ProductionHrDocumentsSeeder - never run this against
 * production.
 */
class HrDocumentManagerTestAccountSeeder extends Seeder
{
    public function run(): void
    {
        $roleId = Role::where('name', 'hr-document-manager')->value('id');

        $user = User::where('email', 'test.hrdocs@webpenter.test')->first()
            ?? new User(['name' => 'Test HR Documents', 'email' => 'test.hrdocs@webpenter.test']);

        $user->role_id = $roleId;
        $user->password = Hash::make('Test@12345');
        $user->save();

        $this->command?->info('test.hrdocs@webpenter.test: ready.');
    }
}
