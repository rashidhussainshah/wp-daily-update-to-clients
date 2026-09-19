<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

/**
 * The Financials module (FinancialsController, gated by
 * EnsureFinancialsAccess -> Administrator only) had working routes for the
 * dashboard, charts, and cash tracker, but only "Quick Expense (Mobile)"
 * ever got a sidebar menu item - the rest were only reachable by typing the
 * URL directly.
 *
 * This adds a "Financials" parent menu item and groups all of its
 * sub-pages under it (Charts & Bank, Cash Tracker, Quick Expense).
 *
 * Every item here also needs its own `permissions` row granted only to
 * Administrator: Voyager's menu check (TCG\Voyager\Policies\MenuItemPolicy)
 * derives a permission key from the item's URL by stripping "/admin" and
 * all slashes (e.g. /admin/financials/charts -> browse_financialscharts)
 * and FAILS OPEN - shows the item to every logged-in user - when no row
 * with that key exists yet. Run --class=Database\Seeders\FinancialsMenuSeeder
 * only (never a blanket db:seed).
 */
class FinancialsMenuSeeder extends Seeder
{
    private array $children = [
        ['title' => 'Charts & Bank', 'url' => '/admin/financials/charts', 'icon_class' => 'voyager-line-chart', 'permission_key' => 'browse_financialscharts', 'order' => 1],
        ['title' => 'Cash Tracker',  'url' => '/admin/financials/cash',   'icon_class' => 'voyager-wallet',      'permission_key' => 'browse_financialscash',   'order' => 2],
    ];

    public function run(): void
    {
        $menu = Menu::where('name', 'admin')->first();
        if (!$menu) {
            $this->command?->warn('Admin menu not found, skipping.');
            return;
        }

        $adminRoleId = DB::table('roles')->where('name', User::ADMINISTRATOR_ROLE_NAME)->value('id');

        $parent = MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Financials'],
            [
                'url'        => '/admin/financials',
                'target'     => '_self',
                'icon_class' => 'voyager-wallet',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 101,
            ]
        );
        $this->grantPermission('browse_financials', $adminRoleId);

        // Quick Expense already exists (2026_07_21_000001 migration) - just re-parent it.
        MenuItem::where('menu_id', $menu->id)->where('route', 'financials.quick-expense')
            ->update(['parent_id' => $parent->id, 'order' => 3]);

        foreach ($this->children as $child) {
            MenuItem::updateOrCreate(
                ['menu_id' => $menu->id, 'title' => $child['title']],
                [
                    'url'        => $child['url'],
                    'target'     => '_self',
                    'icon_class' => $child['icon_class'],
                    'color'      => null,
                    'parent_id'  => $parent->id,
                    'order'      => $child['order'],
                ]
            );

            $this->grantPermission($child['permission_key'], $adminRoleId);
        }

        $this->command?->info('Financials menu seeded: Financials > Charts & Bank, Cash Tracker, Quick Expense (Mobile) - all Administrator-only.');
    }

    private function grantPermission(string $key, ?int $adminRoleId): void
    {
        $permissionId = DB::table('permissions')->where('key', $key)->value('id');
        if (!$permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'key'        => $key,
                'table_name' => str_replace('browse_', '', $key),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($adminRoleId) {
            DB::table('permission_role')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id'       => $adminRoleId,
            ]);
        }
    }
}
