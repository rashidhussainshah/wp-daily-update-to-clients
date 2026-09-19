<?php

namespace Database\Seeders;

use App\Models\SmtpAccount;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;

class SmtpAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name'         => 'Webpenter — contact@webpenter.com',
                'host'         => 'smtp.titan.email',
                'port'         => 465,
                'encryption'   => 'ssl',
                'username'     => 'contact@webpenter.com',
                'password'     => 'contact@786',
                'from_address' => 'contact@webpenter.com',
                'from_name'    => 'Webpenter',
            ],
            [
                'name'         => 'Ayub — ayub@webpenter.com',
                'host'         => 'smtp.titan.email',
                'port'         => 465,
                'encryption'   => 'ssl',
                'username'     => 'ayub@webpenter.com',
                'password'     => 'Ayub@123',
                'from_address' => 'ayub@webpenter.com',
                'from_name'    => 'Ayub Khokhar',
            ],
            [
                'name'         => 'Ali Hassan — alihassan@webpenter.com',
                'host'         => 'smtp.titan.email',
                'port'         => 465,
                'encryption'   => 'ssl',
                'username'     => 'alihassan@webpenter.com',
                'password'     => 'Google@78600',
                'from_address' => 'alihassan@webpenter.com',
                'from_name'    => 'Ali Hassan Business Developer',
            ],
            [
                'name'         => 'Zahid — zahid@webpenter.com',
                'host'         => 'smtp.titan.email',
                'port'         => 465,
                'encryption'   => 'ssl',
                'username'     => 'zahid@webpenter.com',
                'password'     => 'iampak@786',
                'from_address' => 'zahid@webpenter.com',
                'from_name'    => 'Zahid',
            ],
        ];

        foreach ($accounts as $data) {
            SmtpAccount::updateOrCreate(
                ['username' => $data['username']],
                array_merge($data, ['password' => encrypt($data['password'])])
            );
        }

        // Sidebar menu item
        $menu = Menu::where('name', 'admin')->first();
        if ($menu) {
            MenuItem::firstOrCreate(
                ['menu_id' => $menu->id, 'title' => 'SMTP Accounts'],
                [
                    'url'        => '/admin/smtp-accounts',
                    'target'     => '_self',
                    'icon_class' => 'voyager-mail',
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => 98,
                ]
            );
        }

        $this->command->info('SMTP accounts seeded. Update placeholder passwords via /admin/smtp-accounts.');
    }
}
