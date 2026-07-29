<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds "PayPal" and "Direct" to the Income "Source" dropdown options.
     * Neither needs an entry in Income::CLIENT_SOURCE_MAP - clientSource()
     * already falls back to 'other' (no platform fee) for any source not
     * explicitly mapped, same as Payoneer/Other today.
     */
    public function up(): void
    {
        $dataType = DB::table('data_types')->where('slug', 'incomes')->first();
        if (!$dataType) {
            return;
        }

        $row = DB::table('data_rows')->where('data_type_id', $dataType->id)->where('field', 'source')->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}', true) ?: [];
        $details['options']['paypal'] = 'PayPal';
        $details['options']['direct'] = 'Direct';

        DB::table('data_rows')->where('id', $row->id)->update([
            'details' => json_encode($details),
        ]);
    }

    public function down(): void
    {
        $dataType = DB::table('data_types')->where('slug', 'incomes')->first();
        if (!$dataType) {
            return;
        }

        $row = DB::table('data_rows')->where('data_type_id', $dataType->id)->where('field', 'source')->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}', true) ?: [];
        unset($details['options']['paypal'], $details['options']['direct']);

        DB::table('data_rows')->where('id', $row->id)->update([
            'details' => json_encode($details),
        ]);
    }
};
