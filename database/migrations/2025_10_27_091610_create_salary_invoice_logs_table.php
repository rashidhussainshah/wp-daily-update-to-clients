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
        Schema::create('salary_invoice_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('month'); // Format: Y-m (e.g., 2025-10)
            $table->string('invoice_number')->unique();
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->string('currency', 10);
            $table->string('invoice_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_invoice_logs');
    }
};
