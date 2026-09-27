<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Separate from academy_fee_invoices' per-invoice commission payout -
        // this is for payments NOT tied to a specific student's fee (an
        // advance against future commission, a bonus, a manual adjustment).
        // Administrator-only, same as the invoice-based payout.
        Schema::create('academy_instructor_manual_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['advance', 'bonus', 'adjustment'])->default('advance');
            $table->text('note')->nullable();
            $table->string('proof_path')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_instructor_manual_payments');
    }
};
