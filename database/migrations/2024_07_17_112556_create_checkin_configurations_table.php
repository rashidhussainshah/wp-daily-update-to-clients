<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkin_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('developer_id')->unique(); // Add unique constraint here
            $table->string('slack_webhook_url');
            $table->string('designation');
            $table->timestamps();

            // Adding the foreign key constraint
            $table->foreign('developer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkin_configurations');
    }
}
