<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // send_time and send_window_start ended up doing the same job (both
        // anchor when a batch starts) — merge into one field. Any row that
        // never got a window explicitly set inherits its existing send_time.
        DB::table('campaign_automations')
            ->whereNull('send_window_start')
            ->update(['send_window_start' => DB::raw('send_time')]);

        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->dropColumn('send_time');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->time('send_time')->default('09:00:00')->after('frequency');
        });

        DB::table('campaign_automations')->update(['send_time' => DB::raw('send_window_start')]);
    }
};
