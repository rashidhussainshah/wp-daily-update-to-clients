<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smtp_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Display label, e.g. Ayub - ayub@webpenter.com');
            $table->string('host');
            $table->unsignedSmallInteger('port')->default(465);
            $table->enum('encryption', ['ssl', 'tls', 'starttls'])->default('ssl');
            $table->string('username');
            $table->text('password')->comment('Laravel encrypt()');
            $table->string('from_address');
            $table->string('from_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_accounts');
    }
};
