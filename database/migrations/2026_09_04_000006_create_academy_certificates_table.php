<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per issued certificate - a student can hold many (several
        // course certificates plus one track certificate), so this is its own
        // table rather than a couple of columns bolted onto the enrollment.
        Schema::create('academy_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Set for a full-track certificate (A4); null for a course certificate.
            $table->foreignId('enrollment_id')->nullable()->constrained('developer_academy_enrollments')->nullOnDelete();
            // Set for a course certificate (A9); null for a full-track certificate.
            $table->foreignId('course_id')->nullable()->constrained('academy_courses')->nullOnDelete();

            $table->enum('type', ['track', 'course']);
            $table->string('title'); // snapshot of the track/course name at issue time
            $table->string('recipient_name'); // editable per A9 - not locked to users.name
            $table->string('verify_code', 40)->unique();
            $table->string('pdf_path')->nullable();

            // Who issued it - null for an auto-fired track certificate (A4);
            // the HR user for a manually-issued course certificate (A9).
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_certificates');
    }
};
