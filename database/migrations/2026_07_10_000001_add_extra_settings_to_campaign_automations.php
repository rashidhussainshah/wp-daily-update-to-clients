<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->boolean('skip_weekends')->default(false)->after('email_delay_seconds');
            $table->smallInteger('daily_send_cap')->unsigned()->nullable()->after('skip_weekends');
            $table->string('notify_email', 150)->nullable()->after('daily_send_cap');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_automations', function (Blueprint $table) {
            $table->dropColumn(['skip_weekends', 'daily_send_cap', 'notify_email']);
        });
    }
};
