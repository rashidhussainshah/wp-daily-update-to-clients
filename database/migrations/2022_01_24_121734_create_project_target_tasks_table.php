<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTargetTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_target_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_target_id');
            $table->unsignedBigInteger('developer_id');
            $table->text('description');
            $table->date('date');
            $table->string('hours');
            $table->string('minutes');
            $table->foreign('developer_id')->references('id')->on('users');
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
        Schema::dropIfExists('project_target_tasks');
    }
}
