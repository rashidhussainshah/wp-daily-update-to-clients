<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlackFieldsIntoEODConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eod_configurations', function (Blueprint $table) {
            $table->boolean('enable_slack')->default(false)->after('subject')->comment('if 1 then send eod to slack channel');
            $table->string('slack_webhook_url')->after('enable_slack')->nullable()->comment('slack app webhook url to send eod to slack channel');
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
            $table->dropColumn(['enable_slack', 'slack_webhook_url']);
        });
    }
}
