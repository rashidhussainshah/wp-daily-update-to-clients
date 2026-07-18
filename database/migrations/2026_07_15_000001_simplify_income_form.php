<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Simplify the income form: hide Transaction Id and Converted PKR
     * (converted_pkr stays in the table and is now auto-computed from
     * amount x conversion_rate for tracking), relabel Transaction Date
     * to Order Date, and make user_id nullable since the user selection
     * is not part of the form.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE incomes MODIFY user_id BIGINT UNSIGNED NULL COMMENT 'income save into user id'");

        $dataTypeId = DB::table('data_types')->where('slug', 'incomes')->value('id');
        if (!$dataTypeId) {
            return;
        }

        $row = fn(string $field) => DB::table('data_rows')
            ->where('data_type_id', $dataTypeId)->where('field', $field);

        $row('transaction_id')->update(['add' => 0, 'edit' => 0]);
        $row('converted_pkr')->update(['add' => 0, 'edit' => 0]);
        $row('transaction_date')->update(['display_name' => 'Order Date']);
        $row('conversion_rate')->update(['display_name' => 'Conversion Rate (USD to PKR)']);
    }

    public function down(): void
    {
        DB::table('incomes')->whereNull('user_id')->update(['user_id' => 0]);
        DB::statement("ALTER TABLE incomes MODIFY user_id BIGINT UNSIGNED NOT NULL COMMENT 'income save into user id'");

        $dataTypeId = DB::table('data_types')->where('slug', 'incomes')->value('id');
        if (!$dataTypeId) {
            return;
        }

        $row = fn(string $field) => DB::table('data_rows')
            ->where('data_type_id', $dataTypeId)->where('field', $field);

        $row('transaction_id')->update(['add' => 1, 'edit' => 1]);
        $row('converted_pkr')->update(['add' => 1, 'edit' => 1]);
        $row('transaction_date')->update(['display_name' => 'Transaction Date']);
        $row('conversion_rate')->update(['display_name' => 'Conversion Rate']);
    }
};
