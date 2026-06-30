<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class CampaignAutomationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::where('name', 'admin')->first();
        if (!$menu) {
            $this->command->warn('Admin menu not found — skipping.');
            return;
        }

        MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Campaign Automations'],
            [
                'url'        => '/admin/campaign-automations',
                'target'     => '_self',
                'icon_class' => 'voyager-mail',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 99,
            ]
        );

        $this->command->info('Campaign Automations sidebar item added.');
    }
}
