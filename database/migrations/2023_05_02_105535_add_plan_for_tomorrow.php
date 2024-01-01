<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanForTomorrow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eods', function (Blueprint $table) {
            $table->text('plan_for_tomorrow')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eods', function (Blueprint $table) {
            $table->dropColumn('plan_for_tomorrow');
        });
    }
}
