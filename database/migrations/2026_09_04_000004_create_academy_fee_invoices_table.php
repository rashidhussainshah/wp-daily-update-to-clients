<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('developer_academy_enrollments')->cascadeOnDelete();

            // First-of-month marker for the billing period this invoice covers.
            $table->date('month');
            $table->decimal('registration_fee_amount', 10, 2)->default(0);
            $table->decimal('monthly_fee_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);

            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            // Whoever holds the HR/Fee Admin role, or an Administrator - see A6.
            $table->foreignId('marked_paid_by')->nullable()->constrained('users')->nullOnDelete();

            // Instructor commission tracked directly here rather than through
            // UserPayment/Income - that system assumes a client-project income
            // with a platform fee, which doesn't fit a student fee at all.
            $table->decimal('instructor_commission_amount', 10, 2)->nullable();
            $table->boolean('instructor_commission_credited')->default(false);

            $table->timestamps();

            $table->unique(['enrollment_id', 'month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_fee_invoices');
    }
};
