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
        Schema::table('contracts', function (Blueprint $table) {
            $table->text('bank_detail')->nullable()->after('attachments');    
            $table->text('bank_detail_attachments')->nullable()->after('bank_detail');    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('bank_detail');
            $table->dropColumn('bank_detail_attachments');
        });
    }
};
