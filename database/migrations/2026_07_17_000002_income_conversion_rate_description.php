<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $newDescription = 'The rate we get from the local person: we send them the USD amount and '
        . 'they send us back PKR at this rate. Confirm this rate with Rashid or Zahid before saving if unsure.';

    private string $oldDescription = 'The USD to PKR rate used to convert this income to local currency '
        . 'for the payment request payout. If unsure of the correct rate, confirm with Rashid or Zahid before saving.';

    public function up(): void
    {
        $this->setDescription($this->newDescription);
    }

    public function down(): void
    {
        $this->setDescription($this->oldDescription);
    }

    private function setDescription(string $description): void
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'incomes')->value('id');
        $query = DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', 'conversion_rate');
        $row = $query->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}') ?: new stdClass();
        $details->description = $description;
        $query->update(['details' => json_encode($details)]);
    }
};
