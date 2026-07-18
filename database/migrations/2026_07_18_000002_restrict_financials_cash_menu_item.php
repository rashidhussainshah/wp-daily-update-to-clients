<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The "Cash Tracker" menu item (route financials.cash) was visible to
     * every logged-in user because Voyager's menu permission check fails
     * open when the derived permission key doesn't exist yet - meanwhile the
     * actual page (EnsureFinancialsAccess middleware) only allows 2 hardcoded
     * emails, both of whom are the only 2 Administrators in the system.
     * Creating this permission and granting it to Administrator only makes
     * the menu match the page's real access.
     */
    private string $permissionKey = 'browse_financialscash';

    public function up(): void
    {
        $permissionId = DB::table('permissions')->where('key', $this->permissionKey)->value('id');
        if (!$permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'key' => $this->permissionKey,
                'table_name' => 'financialscash',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $adminRoleId = DB::table('roles')->where('name', \App\Models\User::ADMINISTRATOR_ROLE_NAME)->value('id');
        if ($adminRoleId) {
            DB::table('permission_role')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
            ]);
        }
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('key', $this->permissionKey)->value('id');
        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
