<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Academy capabilities (instructor/reviewer) are ADDITIVE, separate from
        // a user's one primary Voyager role_id - a Developer-role user like
        // Ahmad Raza can also be an Academy Reviewer without losing their
        // Developer-role access, since Voyager only supports one role per user.
        Schema::create('academy_staff_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('capability', ['instructor', 'reviewer']);
            $table->timestamps();

            $table->unique(['user_id', 'capability']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_staff_roles');
    }
};
