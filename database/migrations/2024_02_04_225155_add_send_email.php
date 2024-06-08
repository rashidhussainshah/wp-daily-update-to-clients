<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eod_configurations', function (Blueprint $table) {
            $table->boolean('is_send_email')->default(1)->nullable()->after('enable_slack');
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
            $table->dropColumn('is_send_email');
        });
    }
}
