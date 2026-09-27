<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Card Manager assembles print batches (track/instructor filters +
        // manual add); Printer (added previously) actually prints them.
        // Two separate jobs, same additive-capability pattern.
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer', 'accountant', 'marketing', 'printer', 'card_manager')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer', 'accountant', 'marketing', 'printer')");
    }
};
