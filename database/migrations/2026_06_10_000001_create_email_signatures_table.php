<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('sender_email')->unique(); // must match campaign from_email
            $table->string('display_name');
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_email')->nullable(); // email shown in signature body
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('tagline')->nullable();
            $table->string('photo_path')->nullable();        // profile photo
            $table->string('handwritten_path')->nullable();  // handwritten signature image
            $table->enum('template', ['classic', 'minimal', 'bold'])->default('classic');
            $table->string('accent_color', 20)->default('#1a1a2e');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_signatures');
    }
};
