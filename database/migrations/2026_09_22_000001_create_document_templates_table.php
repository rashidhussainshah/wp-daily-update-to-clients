<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Generic document-template engine (experience letters, relieving letters,
 * internship letters, and whatever HR invents next) - `category` is free
 * text, not an enum, so a new letter type is just a new row, never a code
 * change. `body` holds HTML with {{token}} placeholders resolved at
 * issuance time by DocumentTemplateService.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // e.g. experience_letter, relieving_letter - HR's own words, not a hardcoded list
            $table->string('design')->default('classic'); // which letterhead wrapper renders this template
            $table->longText('body'); // HTML with {{token}} placeholders
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
