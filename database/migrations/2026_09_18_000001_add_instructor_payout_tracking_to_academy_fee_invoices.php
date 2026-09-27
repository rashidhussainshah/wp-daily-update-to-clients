<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // instructor_commission_credited only ever meant "calculated and
        // attributed" - it says nothing about whether the instructor was
        // actually paid that money. This tracks the real payout,
        // separately, at the same per-student-per-month granularity the
        // commission itself is already calculated at.
        Schema::table('academy_fee_invoices', function (Blueprint $table) {
            $table->timestamp('instructor_paid_at')->nullable()->after('instructor_commission_credited');
            $table->foreignId('instructor_paid_by')->nullable()->after('instructor_paid_at')->constrained('users')->nullOnDelete();
            $table->string('instructor_payout_proof_path')->nullable()->after('instructor_paid_by');
        });
    }

    public function down(): void
    {
        Schema::table('academy_fee_invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('instructor_paid_by');
            $table->dropColumn(['instructor_paid_at', 'instructor_payout_proof_path']);
        });
    }
};
