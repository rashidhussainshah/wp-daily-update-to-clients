<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // deleted_at has no index — causes full table scan on every paginated COUNT(*)
            $table->index('deleted_at', 'users_deleted_at_index');

            // Composite index for role-scoped queries (used by campaigns + scopes)
            $table->index(['role_id', 'deleted_at'], 'users_role_id_deleted_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_deleted_at_index');
            $table->dropIndex('users_role_id_deleted_at_index');
        });
    }
};
