<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * incomes.show_to_dev had a DB-level schema default of '0' (a leftover
     * from before this field's real default became "yes" at the BREAD
     * config level) - the two are separate mechanisms: BREAD's
     * details.default only pre-selects the radio button on a fresh Add
     * form, it does not change what the column falls back to if the field
     * is ever omitted from a submission. That mismatch let rows silently
     * save as '0' - neither "yes" nor "no" - making them invisible to the
     * payment-request income dropdown (scopeOnlyShowToDev() only matches
     * 'yes'). Fixes the column default AND backfills the already-affected
     * rows.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE incomes MODIFY show_to_dev VARCHAR(255) NOT NULL DEFAULT 'yes'");

        DB::table('incomes')->where('show_to_dev', '0')->update(['show_to_dev' => 'yes']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE incomes MODIFY show_to_dev VARCHAR(255) NOT NULL DEFAULT '0'");
        // Backfilled rows are not reverted - '0' was never a meaningful
        // value, so there is nothing correct to roll back to.
    }
};
