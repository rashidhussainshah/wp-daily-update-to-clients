<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTomorrowWorkPlanToCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->text('tomorrow_work_plan')->nullable()->after('end_of_day_report');
        });
    }

    public function down()
    {
        Schema::table('checkins', function (Blueprint $table) {
            $table->dropColumn('tomorrow_work_plan');
        });
    }
}
