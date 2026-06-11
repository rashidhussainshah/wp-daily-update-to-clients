<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bank_balances', function (Blueprint $table) {
            // Allow recording balance on any specific date, not just month-end
            $table->date('recorded_on')->nullable()->after('month');
            // Drop the unique constraint on month+account so multiple dates per month are allowed
            $table->dropUnique(['month', 'account']);
            // Add cash account support — just another account key
        });
    }

    public function down(): void
    {
        Schema::table('bank_balances', function (Blueprint $table) {
            $table->dropColumn('recorded_on');
            $table->unique(['month', 'account']);
        });
    }
};
