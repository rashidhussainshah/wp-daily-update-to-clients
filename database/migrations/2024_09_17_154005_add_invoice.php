<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->string('attachment');
        });

        Schema::table('online_student_fees', function (Blueprint $table) {
            $table->string('attachment');
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
            $table->dropColumn('attachment');
        });

        Schema::table('online_student_fees', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
}
