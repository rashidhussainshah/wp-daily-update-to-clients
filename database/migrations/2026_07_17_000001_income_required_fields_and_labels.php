<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Makes the core income fields compulsory (transaction id, order date,
     * conversion rate - source/note were already required) and attaches
     * explanatory tooltips to transaction id and conversion rate, per
     * Rashid's request so the accountant/administrator knows exactly what
     * each field means before entering data.
     */
    public function up(): void
    {
        $this->setRow('transaction_id', function ($details) {
            $details->validation = (object) ['rule' => 'required'];
            $details->description = 'This is the Order ID on Fiverr, or the Contract ID on Upwork. '
                . 'Used to detect duplicate entries.';
        }, required: 1);

        $this->setRow('transaction_date', function ($details) {
            $details->validation = (object) ['rule' => 'required|date'];
        }, required: 1);

        $this->setRow('conversion_rate', function ($details) {
            $details->validation = (object) ['rule' => 'required|numeric|min:1'];
            $details->description = 'The USD to PKR rate used to convert this income to local currency '
                . 'for the payment request payout. If unsure of the correct rate, confirm with Rashid or Zahid before saving.';
        }, required: 1);

        // Attachments: required=1 so Voyager's own multiple_images field only
        // asks for a new file when none exist yet (see multiple_images.blade.php) -
        // NOT a "required" validation.rule, which would wrongly block saving an
        // edit that keeps its existing attachments untouched. The hard "at least
        // one attachment" rule is enforced in the Income model's saving hook.
        $this->setRow('attachments', function ($details) {
            // no validation rule change
        }, required: 1);
    }

    public function down(): void
    {
        $this->setRow('transaction_id', function ($details) {
            unset($details->validation);
            unset($details->description);
        }, required: 0);

        $this->setRow('transaction_date', function ($details) {
            unset($details->validation);
        }, required: 0);

        $this->setRow('conversion_rate', function ($details) {
            unset($details->validation);
            unset($details->description);
        }, required: 0);

        $this->setRow('attachments', function ($details) {
            // no validation rule change
        }, required: 0);
    }

    private function setRow(string $field, Closure $mutate, int $required): void
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'incomes')->value('id');
        $query = DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', $field);
        $row = $query->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}') ?: new stdClass();
        $mutate($details);
        $query->update(['details' => json_encode($details), 'required' => $required]);
    }
};
