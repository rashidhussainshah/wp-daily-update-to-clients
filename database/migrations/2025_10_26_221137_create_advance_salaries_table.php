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
        Schema::create('advance_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('month'); // Format: Y-m (e.g., 2025-10)
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid', 'deducted'])->default('pending');
            $table->date('request_date');
            $table->date('approved_date')->nullable();
            $table->date('deducted_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_salaries');
    }
};
