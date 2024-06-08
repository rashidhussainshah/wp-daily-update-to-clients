<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->dateTime('start_date')->nullable()->after('title');
            $table->dateTime('end_date')->nullable()->after('start_date');
            $table->string('currency')->nullable()->after('end_date');
            $table->string('status')->nullable()->after('currency');
            $table->text('attachments')->nullable()->after('status');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('currency');
            $table->dropColumn('status');
            $table->dropColumn('attachments');
            $table->dropSoftDeletes();
        });
    }
}
