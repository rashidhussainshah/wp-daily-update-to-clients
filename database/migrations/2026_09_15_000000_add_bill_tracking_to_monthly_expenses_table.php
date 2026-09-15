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
        Schema::table('monthly_expenses', function (Blueprint $table) {
            $table->decimal('expected_amount_pkr', 12, 2)->nullable()->after('amount_pkr');
            $table->unsignedBigInteger('parent_expense_id')->nullable()->after('expected_amount_pkr');
            $table->boolean('is_advance')->default(false)->after('is_fixed');
            $table->json('attachments')->nullable()->after('note');

            $table->index('parent_expense_id');
            $table->foreign('parent_expense_id')->references('id')->on('monthly_expenses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_expenses', function (Blueprint $table) {
            $table->dropForeign(['parent_expense_id']);
            $table->dropIndex(['parent_expense_id']);
            $table->dropColumn(['expected_amount_pkr', 'parent_expense_id', 'is_advance', 'attachments']);
        });
    }
};
