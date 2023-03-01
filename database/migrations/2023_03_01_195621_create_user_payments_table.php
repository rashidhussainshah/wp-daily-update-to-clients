<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id');
            $table->foreignId('project_id');
            $table->foreignId('project_target_id');
            $table->unsignedFloat('total_earning');
            $table->integer('payable_to_developer')->comment('value will be in dollars and other currencies etc ');
            $table->integer('currency_current_rate')->comment('currency current rates');
            $table->integer('fee')->comment('fiverr 20% upwork 20% 0 for direct clients');
            $table->string('status')->default('Requested');
            $table->longText('notes')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('user_payments');
    }
}
