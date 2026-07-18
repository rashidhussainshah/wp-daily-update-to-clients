<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_payment_id')->constrained('user_payments')->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // created, updated, deleted
            $table->json('changes')->nullable(); // {field: {old, new}}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_payment_logs');
    }
};
