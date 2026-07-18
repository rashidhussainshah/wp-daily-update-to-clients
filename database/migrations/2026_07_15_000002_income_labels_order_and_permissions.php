<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * - Restore Transaction Id on the income form (used by the duplicate
     *   guard: same transaction id + amount + order date is blocked).
     * - Make the income Note required (it is the dropdown label users see).
     * - Income dropdown on payment requests shows "#id - note - $amount - date"
     *   (dropdown_label accessor) instead of the bare note.
     * - Project / target / income dropdowns load newest-first via scopes.
     * - Accountant gets browse/read/add/edit on incomes.
     */
    public function up(): void
    {
        // Restore the field, and drop the old strict-unique rule: one
        // transaction id can legitimately contain multiple orders (Fiverr
        // added hours, several orders in one Upwork contract). Duplicates
        // are instead blocked by the Income model when transaction id,
        // amount AND order date are all identical.
        if ($txn = $this->incomeRow('transaction_id')->first()) {
            $details = json_decode($txn->details ?? '{}') ?: new stdClass();
            unset($details->validation);
            $this->incomeRow('transaction_id')->update([
                'add' => 1,
                'edit' => 1,
                'details' => json_encode($details),
            ]);
        }

        if ($note = $this->incomeRow('note')->first()) {
            $details = json_decode($note->details ?? '{}') ?: new stdClass();
            $details->validation = (object) ['rule' => 'required'];
            $this->incomeRow('note')->update(['required' => 1, 'details' => json_encode($details)]);
        }

        $this->setPaymentRelationDetail('user_payment_belongsto_income_relationship', function ($details) {
            $details->label = 'dropdown_label';
            $details->scope = 'onlyShowToDev';
        });
        $this->setPaymentRelationDetail('user_payment_belongsto_project_relationship', function ($details) {
            $details->scope = 'latestFirst';
        });
        $this->setPaymentRelationDetail('user_payment_belongsto_project_target_relationship', function ($details) {
            $details->scope = 'latestFirst';
        });

        $this->setAccountantIncomePermissions(true);
    }

    public function down(): void
    {
        if ($txn = $this->incomeRow('transaction_id')->first()) {
            $details = json_decode($txn->details ?? '{}') ?: new stdClass();
            $details->validation = (object) ['rule' => 'unique:incomes,transaction_id'];
            $this->incomeRow('transaction_id')->update([
                'add' => 0,
                'edit' => 0,
                'details' => json_encode($details),
            ]);
        }

        if ($note = $this->incomeRow('note')->first()) {
            $details = json_decode($note->details ?? '{}') ?: new stdClass();
            unset($details->validation);
            $this->incomeRow('note')->update(['required' => 0, 'details' => json_encode($details)]);
        }

        $this->setPaymentRelationDetail('user_payment_belongsto_income_relationship', function ($details) {
            $details->label = 'note';
            unset($details->scope);
        });
        $this->setPaymentRelationDetail('user_payment_belongsto_project_relationship', function ($details) {
            unset($details->scope);
        });
        $this->setPaymentRelationDetail('user_payment_belongsto_project_target_relationship', function ($details) {
            unset($details->scope);
        });

        $this->setAccountantIncomePermissions(false);
    }

    private function incomeRow(string $field)
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'incomes')->value('id');

        return DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', $field);
    }

    private function setPaymentRelationDetail(string $field, Closure $mutate): void
    {
        $dataTypeId = DB::table('data_types')->where('slug', 'user-payments')->value('id');
        $query = DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', $field);
        $row = $query->first();
        if (!$row) {
            return;
        }

        $details = json_decode($row->details ?? '{}') ?: new stdClass();
        $mutate($details);
        $query->update(['details' => json_encode($details)]);
    }

    private function setAccountantIncomePermissions(bool $grant): void
    {
        $roleId = DB::table('roles')->where('name', \App\Models\User::ACCOUNTANT_ROLE_NAME)->value('id');
        if (!$roleId) {
            return;
        }

        $permissionIds = DB::table('permissions')
            ->whereIn('key', ['browse_incomes', 'read_incomes', 'add_incomes', 'edit_incomes'])
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            if ($grant) {
                DB::table('permission_role')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            } else {
                DB::table('permission_role')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $roleId)
                    ->delete();
            }
        }
    }
};
