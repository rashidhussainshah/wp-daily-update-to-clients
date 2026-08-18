<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            // Set when a batch is dispatched to the queue, cleared once the job
            // finishes (success or exhausted retries) — lets the UI show whether
            // a batch is currently sitting in the queue / being sent right now.
            $table->timestamp('queued_at')->nullable()->after('next_run_at');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->dropColumn('queued_at');
        });
    }
};
