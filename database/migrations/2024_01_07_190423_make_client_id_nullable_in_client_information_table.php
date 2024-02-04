<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeClientIdNullableInClientInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the column exists before modifying it
        if (Schema::hasColumn('client_information', 'client_id')) {
            Schema::table('client_information', function (Blueprint $table) {
                // Drop the existing foreign key constraint
                $table->dropForeign(['client_id']);

                // Modify 'client_id' column to be nullable
                $table->unsignedBigInteger('client_id')->nullable()->change();
            });
        } else {
            // If the column doesn't exist, create it
            Schema::table('client_information', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reversing the migration can be complex, you may need to adjust this based on your actual requirements
        // For now, this is an example of dropping the nullable constraint
        Schema::table('client_information', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->change();
        });
    }
}
