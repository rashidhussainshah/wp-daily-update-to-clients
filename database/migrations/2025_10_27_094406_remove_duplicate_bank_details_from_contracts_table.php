<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, copy data from bank_details to bank_detail if bank_detail is null
        DB::statement('UPDATE contracts SET bank_detail = bank_details WHERE bank_detail IS NULL AND bank_details IS NOT NULL');

        // Then drop the duplicate bank_details column
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('bank_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->text('bank_details')->nullable()->after('monthly_salary');
        });
    }
};
