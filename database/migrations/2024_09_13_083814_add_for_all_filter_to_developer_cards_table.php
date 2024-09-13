<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForAllFilterToDeveloperCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('developer_cards', function (Blueprint $table) {
            $table->boolean('for_all_filter')->default(true); // Adding the for_all_filter column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('developer_cards', function (Blueprint $table) {
            $table->dropColumn('for_all_filter');
        });
    }
}
