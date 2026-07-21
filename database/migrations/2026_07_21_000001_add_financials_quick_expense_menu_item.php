<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds the mobile "Quick Expense" page to the admin sidebar (under the
     * same top-level Financials group as "Charts & Bank" / "Cash Tracker")
     * and restricts its visibility to Administrator, mirroring the fix in
     * 2026_07_18_000002_restrict_financials_cash_menu_item.php - without a
     * matching permission row, Voyager's menu check fails open and shows
     * the item to every logged-in user even though the page itself
     * (EnsureFinancialsAccess middleware) only allows 2 admin accounts.
     */
    private string $permissionKey = 'browse_financialsquick-expense';
    private string $tableName = 'financialsquick-expense';
    private string $menuTitle = 'Quick Expense (Mobile)';

    public function up(): void
    {
        $menuItemId = DB::table('menu_items')->where('route', 'financials.quick-expense')->value('id');
        if (!$menuItemId) {
            DB::table('menu_items')->insert([
                'menu_id'    => 1,
                'title'      => $this->menuTitle,
                'url'        => '',
                'target'     => '_self',
                'icon_class' => 'voyager-phone',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 102,
                'route'      => 'financials.quick-expense',
                'parameters' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $permissionId = DB::table('permissions')->where('key', $this->permissionKey)->value('id');
        if (!$permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'key'        => $this->permissionKey,
                'table_name' => $this->tableName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $adminRoleId = DB::table('roles')->where('name', \App\Models\User::ADMINISTRATOR_ROLE_NAME)->value('id');
        if ($adminRoleId) {
            DB::table('permission_role')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id'       => $adminRoleId,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'financials.quick-expense')->delete();

        $permissionId = DB::table('permissions')->where('key', $this->permissionKey)->value('id');
        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }
    }
};
