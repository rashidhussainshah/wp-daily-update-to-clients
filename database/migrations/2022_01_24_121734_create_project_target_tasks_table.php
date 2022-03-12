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
            $table->string('title');
            $table->enum('status', ['In Progress', 'QA', 'Completed'])->default('In Progress');
            $table->enum('type', ['Bug', 'Development', 'Enhancement', 'Feature', 'Issue', 'Modification', 'Meeting'])->default('Bug');
            $table->text('description');
            $table->string('attachment_files')->nullable();

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
