<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            // Which Blade template rendered this PDF - lets Marketing pick a
            // look per certificate and filter the gallery by it. New designs
            // are just new template files + this list, no schema change.
            $table->string('design')->default('classic')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('academy_certificates', function (Blueprint $table) {
            $table->dropColumn('design');
        });
    }
};
