<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            // Optional window constraining what time of day sending is allowed —
            // independent of Send Time, which only controls when a batch *starts*.
            // Keeps a long, delay-spread batch from trickling into off-hours.
            $table->time('send_window_start')->nullable()->after('send_time');
            $table->time('send_window_end')->nullable()->after('send_window_start');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->dropColumn(['send_window_start', 'send_window_end']);
        });
    }
};
