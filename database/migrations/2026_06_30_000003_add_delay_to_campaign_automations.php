<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->unsignedSmallInteger('email_delay_seconds')->default(0)
                  ->after('resend_gap_days')
                  ->comment('Seconds to wait between individual emails. 0 = send all at once.');
            $table->unsignedSmallInteger('emails_sent_in_batch')->default(0)
                  ->after('email_delay_seconds')
                  ->comment('Tracks progress within the current batch when delay > 0.');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->dropColumn(['email_delay_seconds', 'emails_sent_in_batch']);
        });
    }
};
