<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperCardsTable extends Migration
{
    public function up()
    {
        Schema::create('developer_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('developer_category_id'); // Foreign key to developer_categories table
            $table->string('name');
            $table->string('designation');
//            $table->json('expertises'); // Assuming you still want this field
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('developer_category_id')->references('id')->on('developer_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('developer_cards');
    }
}
