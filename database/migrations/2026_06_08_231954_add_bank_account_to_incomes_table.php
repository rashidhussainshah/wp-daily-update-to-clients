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
        Schema::table('incomes', function (Blueprint $table) {
            // Which bank account received the converted PKR
            // rashid_al_habib | rashid_meezan | zahid_allied
            $table->string('received_in')->nullable()->after('source');
            $table->decimal('converted_pkr', 14, 2)->nullable()->after('received_in');
            $table->decimal('conversion_rate', 10, 4)->nullable()->after('converted_pkr');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn(['received_in', 'converted_pkr', 'conversion_rate']);
        });
    }
};
