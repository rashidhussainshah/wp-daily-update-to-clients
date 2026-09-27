<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->timestamp('posted_at')->nullable()->after('design');
            $table->foreignId('posted_by')->nullable()->after('posted_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('posted_by');
            $table->dropColumn('posted_at');
        });
    }
};
