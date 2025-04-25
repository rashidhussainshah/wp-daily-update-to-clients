<?php
// database/migrations/xxxx_xx_xx_create_client_portfolio_developer_card_expertise_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPortfolioDeveloperCardExpertiseTable extends Migration
{
    public function up()
    {
        Schema::create('client_portfolio_developer_card_expertise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portfolio_id')
                ->constrained('client_portfolios')  // Specify the related table
                ->onDelete('cascade')
                ->name('client_portfolio_id_fk'); // Shortened name for the foreign key constraint
            $table->foreignId('developer_card_expertise_id')
                ->constrained('developer_card_expertise')  // Specify the related table
                ->onDelete('cascade')
                ->name('developer_card_expertise_id_fk'); // Shortened name for the foreign key constraint
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('client_portfolio_developer_card_expertise');
    }
}
