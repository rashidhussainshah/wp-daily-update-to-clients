<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'coo_required')) {
                $table->boolean('coo_required')->default(false)->after('reason');
            }
            if (!Schema::hasColumn('leaves', 'coo_approved_at')) {
                $table->timestamp('coo_approved_at')->nullable()->after('coo_required');
            }
            if (!Schema::hasColumn('leaves', 'coo_approved_by')) {
                $table->unsignedBigInteger('coo_approved_by')->nullable()->after('coo_approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'coo_required')) {
                $table->dropColumn('coo_required');
            }
            if (Schema::hasColumn('leaves', 'coo_approved_at')) {
                $table->dropColumn('coo_approved_at');
            }
            if (Schema::hasColumn('leaves', 'coo_approved_by')) {
                $table->dropColumn('coo_approved_by');
            }
        });
    }
};
