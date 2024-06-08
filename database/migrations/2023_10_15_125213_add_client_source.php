<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_payments', function (Blueprint $table) {
            $table->string('client_source')->nullable()->comment('fiverr, upwork, payoneer, direct')->after('total_earning');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_payments', function (Blueprint $table) {
            $table->dropColumn('client_source');
        });
    }
}
