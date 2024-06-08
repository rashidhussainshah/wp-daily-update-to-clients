<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDefaultSettingForEod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eod_configurations', function (Blueprint $table) {
            $table->boolean('is_default_setting_for_eod')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eod_configurations', function (Blueprint $table) {
            $table->dropColumn('is_default_setting_for_eod');
        });
    }
}
