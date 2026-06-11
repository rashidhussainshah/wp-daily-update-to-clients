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
        Schema::create('monthly_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('month', 7);
            $table->string('category');
            $table->decimal('amount_pkr', 12, 2);
            $table->boolean('is_fixed')->default(false);
            // rashid_al_habib | rashid_meezan | zahid_allied | cash
            $table->string('paid_from')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_expenses');
    }
};
