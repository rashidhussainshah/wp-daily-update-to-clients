<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * An optional free-text line for a skill/achievement certificate - e.g.
 * "Built and shipped the Booking & Rental mobile app end-to-end; live on
 * the Play Store." Plain track/course-completion certificates leave this
 * null and render exactly as before.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->text('achievement_note')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->dropColumn('achievement_note');
        });
    }
};
