<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_automation_logs', function (Blueprint $table) {
            // Supports the cross-campaign resend_gap_days lookup, which filters
            // by email + status + sent_at without an automation_id predicate
            // (the existing automation_id-led index doesn't help that query).
            $table->index(['email', 'status', 'sent_at']);
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automation_logs', function (Blueprint $table) {
            $table->dropIndex(['email', 'status', 'sent_at']);
        });
    }
};
