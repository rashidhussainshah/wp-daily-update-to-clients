<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per generated letter/PDF. `data` snapshots every resolved token
 * value at issue time, so a letter's wording stays accurate even if the
 * employee's contract/user record changes afterwards (mirrors why
 * AcademyCertificate stores its own recipient_name/title snapshot columns).
 *
 * `user_id` is nullable because HR can issue a letter to someone who isn't
 * a User in the system at all (a former employee already deleted, or a
 * one-off case) - `recipient_name`/`recipient_email` are always populated
 * either way (copied from the resolved employee_name/employee_email
 * tokens), so history/search never depends on a live user record existing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_issuances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('document_templates')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete(); // the staff member who generated it
            $table->json('data'); // resolved {{token}} => value snapshot
            $table->string('pdf_path')->nullable();
            $table->string('verify_code', 40)->unique();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_issuances');
    }
};
