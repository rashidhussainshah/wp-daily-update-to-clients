<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('company');
            $table->string('source_domain')->nullable()->after('status');
            $table->string('referer_url')->nullable()->after('source_domain');
            $table->string('ip_address')->nullable()->after('referer_url');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->string('country')->nullable()->after('user_agent');
            $table->string('region')->nullable()->after('country');
            $table->string('city')->nullable()->after('region');
            // Any request field outside the known set (name/email/company/subject/
            // phone/message) — lets other sites (e.g. scriptandtools.com) send
            // fields webpenter.com's form doesn't have without a schema change.
            $table->json('extra_fields')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn([
                'subject',
                'source_domain',
                'referer_url',
                'ip_address',
                'user_agent',
                'country',
                'region',
                'city',
                'extra_fields',
            ]);
        });
    }
};
