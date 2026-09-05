<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developer_academy_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('track_id')->constrained('academy_tracks')->cascadeOnDelete();
            // Copied from the track's default_instructor_id at enrollment time (A13),
            // but kept as its own column since a student can be reassigned later.
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedTinyInteger('current_stage')->default(0);
            // { "0": { "skills": {"skill-key": true}, "projects": {"project-key": "done|in_progress|pending"} }, ... }
            $table->json('progress')->nullable();
            // [{ "title": "Python Core", "awarded_at": "2026-07-02" }, ...]
            $table->json('badges_earned')->nullable();

            $table->enum('status', ['active', 'completed', 'withdrawn'])->default('active');

            // A14 - public parent view link. Stored random token, not a signed URL
            // (matches the same pattern as academy_certificates.verify_code).
            $table->string('parent_view_token', 64)->nullable()->unique();

            $table->timestamp('enrolled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_academy_enrollments');
    }
};
