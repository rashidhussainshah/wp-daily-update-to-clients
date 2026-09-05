<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_ai_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('developer_academy_enrollments')->cascadeOnDelete();
            $table->unsignedTinyInteger('stage_index');
            $table->string('project_title');
            $table->string('submission_link');
            $table->text('notes')->nullable();

            $table->decimal('ai_score', 4, 1)->nullable();
            $table->enum('ai_verdict', ['approve', 'needs_work', 'reject'])->nullable();
            $table->text('ai_feedback')->nullable();

            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('reviewer_status', ['pending', 'approved', 'sent_back'])->default('pending');
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['enrollment_id', 'reviewer_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_ai_reviews');
    }
};
