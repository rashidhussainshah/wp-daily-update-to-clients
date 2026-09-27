<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Same additive-capability pattern as instructor/reviewer - fee
        // collection (accountant) and certificate/social sharing (marketing)
        // are also jobs someone can hold alongside their existing primary
        // role, not exclusive new roles that replace it.
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer', 'accountant', 'marketing')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE academy_staff_roles MODIFY COLUMN capability ENUM('instructor', 'reviewer')");
    }
};
