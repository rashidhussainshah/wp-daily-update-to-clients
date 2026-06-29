<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bd_monthly_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // business developer
            $table->string('month', 7);             // Y-m
            $table->decimal('target_usd', 10, 2);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'month']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bd_monthly_targets');
    }
};
