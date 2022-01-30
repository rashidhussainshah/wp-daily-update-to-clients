<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->unsignedBigInteger('developer_id');
            $table->string('title');
            $table->enum('status', ['In Progress', 'QA', 'Completed'])->default('In Progress');
            $table->enum('type', ['User Story', 'Assignment', 'Milestone', 'Project', 'Sprint', 'Update', 'Feature'])->default('User Story');
            $table->text('description')->nullable();
            $table->string('attachment_files')->nullable();
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
        Schema::dropIfExists('project_targets');
    }
}
