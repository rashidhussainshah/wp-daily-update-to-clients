<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A "print batch" is a named group of students' ID cards a Card Manager
 * assembles (by track/instructor filters, or manually) before handing it
 * off to a Printer. draft = still being assembled; ready = Card Manager
 * says "go print this"; printed = Printer has run it and marked it done.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_card_batches', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->enum('status', ['draft', 'ready', 'printed'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('printed_by')->nullable()->constrained('users');
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('academy_card_batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('academy_card_batches')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_card_batch_items');
        Schema::dropIfExists('academy_card_batches');
    }
};
