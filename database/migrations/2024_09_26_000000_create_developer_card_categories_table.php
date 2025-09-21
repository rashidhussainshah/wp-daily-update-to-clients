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
        if (!Schema::hasTable('developer_card_categories')) {
            Schema::create('developer_card_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('developer_card_id')->constrained('developer_cards')->onDelete('cascade');
                $table->foreignId('developer_category_id')->constrained('developer_categories')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_card_categories');
    }
};
