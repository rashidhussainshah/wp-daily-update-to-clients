<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperCardExpertisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developer_card_expertises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('developer_card_id');
            $table->unsignedBigInteger('expertise_id');
            $table->timestamps();

//            $table->foreign('developer_card_id')->references('id')->on('developer_cards')->onDelete('cascade');
//            $table->foreign('expertise_id')->references('id')->on('expertise')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('developer_card_expertises');
    }
}
