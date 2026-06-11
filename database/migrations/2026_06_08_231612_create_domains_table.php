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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // e.g. webpenter.com
            $table->string('registrar')->nullable();  // Namecheap, GoDaddy, etc.
            $table->date('expires_on');
            $table->decimal('renewal_cost_usd', 8, 2)->nullable();
            $table->decimal('renewal_cost_pkr', 10, 2)->nullable();
            $table->string('project')->nullable();   // Webpenter, BookHere, Houzilo, ScriptAndTools
            $table->string('paid_from')->nullable(); // rashid_al_habib | rashid_meezan | zahid_allied
            $table->boolean('auto_renew')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
