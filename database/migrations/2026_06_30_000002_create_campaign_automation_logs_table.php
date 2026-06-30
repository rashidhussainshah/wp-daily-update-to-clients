<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_id')->constrained('campaign_automations')->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email');
            $table->string('name')->default('');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error')->nullable();
            $table->timestamp('sent_at');
            $table->index(['automation_id', 'email', 'sent_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_automation_logs');
    }
};
