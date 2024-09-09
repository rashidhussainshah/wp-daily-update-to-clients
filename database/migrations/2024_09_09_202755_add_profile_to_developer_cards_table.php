<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileToDeveloperCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_cards', function (Blueprint $table) {
            $table->text('profile')->nullable()->after('designation');
        });
    }

    public function down()
    {
        Schema::table('developer_cards', function (Blueprint $table) {
            $table->dropColumn('profile');
        });
    }
}
