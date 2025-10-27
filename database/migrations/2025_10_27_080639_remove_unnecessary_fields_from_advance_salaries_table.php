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
        Schema::table('advance_salaries', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['approved_by']);
            // Then drop the columns
            $table->dropColumn(['request_date', 'approved_date', 'approved_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_salaries', function (Blueprint $table) {
            $table->date('request_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
        });
    }
};
