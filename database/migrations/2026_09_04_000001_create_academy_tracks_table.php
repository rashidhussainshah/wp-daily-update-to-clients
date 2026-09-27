<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->text('description')->nullable();
            // Curriculum stored as JSON: stages -> [{ title, skills: [], projects: [] }].
            // Kept as JSON rather than fully normalized (skills/projects tables) to
            // avoid over-engineering a small, staff-edited curriculum - see A1's plan.
            $table->json('curriculum')->nullable();

            $table->boolean('is_open_for_enrollment')->default(false);
            $table->boolean('registration_fee_enabled')->default(false);
            $table->decimal('registration_fee_amount', 10, 2)->nullable();
            $table->decimal('monthly_fee_amount', 10, 2)->nullable();
            // Null falls back to the global `academy.default_instructor_commission_percent` setting.
            $table->decimal('instructor_commission_percent', 5, 2)->nullable();

            $table->foreignId('default_instructor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_tracks');
    }
};
