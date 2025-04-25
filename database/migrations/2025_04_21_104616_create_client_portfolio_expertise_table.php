<?php
// database/migrations/xxxx_xx_xx_create_client_portfolio_expertise_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPortfolioExpertiseTable extends Migration
{
    public function up()
    {
        Schema::create('client_portfolio_expertise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('expertise_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_portfolio_expertise');
    }
}
