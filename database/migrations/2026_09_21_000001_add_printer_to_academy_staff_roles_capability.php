<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Same additive-capability pattern as instructor/reviewer/accountant/
        // marketing - printing ID cards for a batch of students is its own
        // job someone can hold alongside (or instead of) another primary
        // role.
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer', 'accountant', 'marketing', 'printer')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer', 'accountant', 'marketing')");
    }
};
