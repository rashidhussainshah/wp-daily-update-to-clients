<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Quick maintenance / repair log for company devices (battery swap, hard
 * drive replacement, etc.) - added directly from the Company Device detail
 * page (resources/views/vendor/voyager/company-devices/read.blade.php), not
 * as a separate BREAD/menu item. Additive only; nothing existing touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('company_device_maintenance_logs')) {
            return;
        }

        Schema::create('company_device_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('company_devices')->cascadeOnDelete();
            $table->string('type', 30)->nullable()->comment('battery / hard_drive / screen / keyboard / ram / other - quick pick');
            $table->text('note')->comment('Free-text details of what was done');
            $table->foreignId('logged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('logged_at');
            $table->timestamps();

            $table->index(['device_id', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_device_maintenance_logs');
    }
};
