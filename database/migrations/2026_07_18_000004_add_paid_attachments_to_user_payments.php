<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Proof-of-payment attachments (invoice/receipt), uploaded when marking
     * a request as paid - separate from the request's own "attachments"
     * (submitter's own files) and the income's attachments (client proof).
     */
    public function up(): void
    {
        Schema::table('user_payments', function (Blueprint $table) {
            $table->longText('paid_attachments')->nullable()->after('paid');
        });

        $dataTypeId = DB::table('data_types')->where('slug', 'user-payments')->value('id');
        if ($dataTypeId && !DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', 'paid_attachments')->exists()) {
            DB::table('data_rows')->insert([
                'data_type_id' => $dataTypeId,
                'field' => 'paid_attachments',
                'type' => 'multiple_images',
                'display_name' => 'Payment Proof',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 0,
                'delete' => 1,
                'details' => json_encode(['display' => ['width' => 12]]),
                'order' => 25,
            ]);
        }
    }

    public function down(): void
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'user-payments')->value('id');
        if ($dataTypeId) {
            DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', 'paid_attachments')->delete();
        }

        Schema::table('user_payments', function (Blueprint $table) {
            $table->dropColumn('paid_attachments');
        });
    }
};
