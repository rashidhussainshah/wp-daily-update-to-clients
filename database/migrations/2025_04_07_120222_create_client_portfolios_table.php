<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable(); // Image path or URL
            $table->json('experience')->nullable(); // Ensure experience is an array
            $table->json('expertise')->nullable(); // Ensure expertise is an array
            $table->json('knowledge')->nullable(); // Ensure knowledge is an array
            $table->json('client_name')->nullable(); // Ensure client names are an array
            $table->json('client_review')->nullable(); // Ensure client reviews are an array
            $table->string('whatsapp')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();

            // Foreign key for user_id
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_portfolios');
    }
}
