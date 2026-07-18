<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Project / Project Target dropdowns on the payment request form show
     * just the name/title today, making same-named records hard to tell
     * apart. Switches the relationship label to the id-prefixed
     * dropdown_label accessor (e.g. "#42 - Homepage Redesign"), same
     * pattern already used for the Income dropdown. Ordering (newest
     * first) is untouched - both already have scope=latestFirst.
     */
    public function up(): void
    {
        $this->setLabel('user_payment_belongsto_project_relationship', 'dropdown_label');
        $this->setLabel('user_payment_belongsto_project_target_relationship', 'dropdown_label');
    }

    public function down(): void
    {
        $this->setLabel('user_payment_belongsto_project_relationship', 'name');
        $this->setLabel('user_payment_belongsto_project_target_relationship', 'title');
    }

    private function setLabel(string $field, string $label): void
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'user-payments')->value('id');
        $query = DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', $field);
        $row = $query->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}') ?: new stdClass();
        $details->label = $label;
        $query->update(['details' => json_encode($details)]);
    }
};
