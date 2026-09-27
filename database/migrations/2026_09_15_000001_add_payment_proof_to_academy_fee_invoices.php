<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Deliberately NOT a new status - the invoice stays 'pending' until
        // the accountant actually marks it paid via the existing
        // markPaid() action. This just gives the accountant something to
        // look at (a screenshot) instead of having to chase it down
        // elsewhere - keeps the whole flow to the two states it already had.
        Schema::table('academy_fee_invoices', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('status');
            $table->timestamp('payment_proof_submitted_at')->nullable()->after('payment_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('academy_fee_invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_proof_path', 'payment_proof_submitted_at']);
        });
    }
};
