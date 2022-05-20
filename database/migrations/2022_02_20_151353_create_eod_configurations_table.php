<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEodConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eod_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->unsignedBigInteger('client_id')->comment('email to client id');
            $table->string('cc')->nullable()->comment('email css address or address');
            $table->string('bcc')->nullable()->comment('email css address or address');
            $table->string('subject')->comment('email subject');
            $table->boolean('task_status')->default(true);
            $table->boolean('task_hours')->default(true);
            $table->string('greetings')->comment('email heading');
            $table->text('signature')->comment('email signature');
            $table->unsignedBigInteger('developer_id')->comment('email send to client from this account');
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
        Schema::dropIfExists('eod_configurations');
    }
}
