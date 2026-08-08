<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The admin Users listing (data_types.slug = users) had its BREAD
     * "scope" set to onlyDeveloper, which restricted /admin/users to only
     * Developer-role users - hiding Clients, Accountants, Administrators,
     * etc. Switches it to excludeHomeyClient instead, so the listing shows
     * every real user and only hides the Homey Client role (id 61), which
     * is used for marketing-campaign leads (see ImportHomeyClients), not
     * portal staff.
     */
    public function up(): void
    {
        $dataType = DB::table('data_types')->where('slug', 'users')->first();
        if (!$dataType) {
            return;
        }

        $details = json_decode($dataType->details ?? '{}', true) ?: [];
        $details['scope'] = 'excludeHomeyClient';

        DB::table('data_types')->where('id', $dataType->id)->update([
            'details' => json_encode($details),
        ]);
    }

    public function down(): void
    {
        $dataType = DB::table('data_types')->where('slug', 'users')->first();
        if (!$dataType) {
            return;
        }

        $details = json_decode($dataType->details ?? '{}', true) ?: [];
        $details['scope'] = 'onlyDeveloper';

        DB::table('data_types')->where('id', $dataType->id)->update([
            'details' => json_encode($details),
        ]);
    }
};
