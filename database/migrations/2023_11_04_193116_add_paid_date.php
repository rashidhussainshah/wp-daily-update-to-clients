<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dateTime('paid_date')->nullable()->after('status');
            $table->foreignId('receiver_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropColumn('paid_date');
        });
    }
}
