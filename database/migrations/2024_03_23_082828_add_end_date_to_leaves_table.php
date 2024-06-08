<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEndDateToLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Rename the existing 'date' column to 'start_date'
            $table->renameColumn('date', 'start_date');

            // Add new 'end_date' column
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Drop the 'end_date' column
            $table->dropColumn('end_date');

            // Rename the 'start_date' column back to 'date'
            $table->renameColumn('start_date', 'date');
        });
    }
}
