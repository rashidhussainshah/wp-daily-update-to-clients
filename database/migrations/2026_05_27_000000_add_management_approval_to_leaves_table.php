<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'management_approval')) {
                $table->enum('management_approval', ['pending', 'approved'])
                    ->default('pending')
                    ->after('coo_approved_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'management_approval')) {
                $table->dropColumn('management_approval');
            }
        });
    }
};
