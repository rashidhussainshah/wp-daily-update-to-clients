<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A lightweight, separate catalog of certifiable courses - deliberately NOT
        // tied to a specific track's stage, since some courses (e.g. Amir Sohail's
        // MS Office course) aren't part of any dev track at all. See A9.
        Schema::create('academy_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // e.g. "Foundation", "Beginner", "Track Stage"
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_courses');
    }
};
