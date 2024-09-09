<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerInformationTable extends Migration
{
    public function up()
    {
        Schema::create('banner_information', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->string('banner_years_experience')->nullable();
            $table->string('banner_name')->nullable();
            $table->string('banner_animated_text')->nullable();
            $table->text('banner_short_description')->nullable();
            $table->string('banner_btn_text')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner_information');
    }
}
