<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staff skill/achievement certificates (for WebPenter's own team - already
 * expert, not academy graduates) drop the "WebPenter IT Academy" branding
 * and just say "WebPenter" - student track/course certificates are
 * unaffected and keep the full Academy branding.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->boolean('is_staff_certificate')->default(false)->after('achievement_note');
        });
    }

    public function down(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->dropColumn('is_staff_certificate');
        });
    }
};
