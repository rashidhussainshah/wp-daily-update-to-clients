<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class EmailCampaignMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::where('name', 'admin')->first();

        if (!$menu) {
            return;
        }

        $item = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Email Campaigns',
            'url'     => '/admin/email-campaigns',
        ]);

        if (!$item->exists) {
            $item->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-mail',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 8,
                'route'      => null,
                'parameters' => null,
            ])->save();
        }
    }
}
