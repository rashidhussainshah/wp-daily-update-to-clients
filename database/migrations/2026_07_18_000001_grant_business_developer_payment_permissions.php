<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Grants the "Bussiness Developer" role the same baseline user_payments
     * permissions the Developer role already has (browse/read/add - never
     * edit/delete, that stays accountant/admin only), so any current or
     * future business developer can submit and view their own payment
     * requests without needing individual per-user permission grants.
     */
    private array $permissionKeys = ['browse_user_payments', 'read_user_payments', 'add_user_payments'];

    public function up(): void
    {
        $this->setGrant(true);
    }

    public function down(): void
    {
        $this->setGrant(false);
    }

    private function setGrant(bool $grant): void
    {
        $roleId = DB::table('roles')->where('name', \App\Models\User::BUSINESS_DEVELOPER_ROLE_NAME)->value('id');
        if (!$roleId) {
            return;
        }

        $permissionIds = DB::table('permissions')->whereIn('key', $this->permissionKeys)->pluck('id');

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
