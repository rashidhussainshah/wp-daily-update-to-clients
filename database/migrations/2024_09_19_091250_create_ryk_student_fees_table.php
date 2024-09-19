<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRykStudentFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ryk_student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id');
            $table->double('amount');
            $table->string('batch');
            $table->dateTime('date')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('paid_date')->nullable();
            $table->foreignId('receiver_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment');
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
        Schema::dropIfExists('ryk_student_fees');
    }
}
