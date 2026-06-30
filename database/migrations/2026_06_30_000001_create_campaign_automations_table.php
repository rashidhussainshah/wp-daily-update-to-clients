<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_automations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->enum('frequency', ['once', 'daily', 'weekly', 'monthly']);
            $table->time('send_time')->default('09:00:00');
            $table->tinyInteger('send_day_of_week')->nullable()->comment('0=Sun,1=Mon...6=Sat — for weekly');
            $table->tinyInteger('send_day_of_month')->nullable()->comment('1-31 — for monthly');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedSmallInteger('batch_size')->default(10)->comment('Emails per run');
            $table->unsignedSmallInteger('resend_gap_days')->default(0)->comment('0 = never resend same user');
            $table->string('target_role')->default('homey_client');
            $table->unsignedInteger('emails_sent_total')->default(0);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_automations');
    }
};
