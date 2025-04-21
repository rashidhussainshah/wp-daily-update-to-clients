<?php
// database/migrations/xxxx_xx_xx_create_client_portfolio_developer_category_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPortfolioDeveloperCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('client_portfolio_developer_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('developer_category_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('fk_client_portfolio_developer_category_developer_category_id'); // Shortened constraint name
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('client_portfolio_developer_category');
    }
}
