<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class EmailSignatureSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Allowed sender emails ────────────────────────────────────────────
        // Add or remove emails here, one per line, then re-run this seeder.
        // The signature creation form will show only these emails as options.
        $emails = implode("\n", [
            'rashid.bukhari78600@gmail.com',
            'alihasanwebpenter@gmail.com',
            'ayubkhokhar786@gmail.com',
            'zaars59208@gmail.com',
        ]);

        Setting::updateOrCreate(
            ['key' => 'signatures.allowed_senders'],
            [
                'display_name' => 'Allowed Sender Emails (one per line)',
                'value'        => $emails,
                'details'      => '',
                'type'         => 'text_area',
                'order'        => 1,
                'group'        => 'Email Signatures',
            ]
        );

        // ── Sidebar menu item ─────────────────────────────────────────────────
        $menu = \TCG\Voyager\Models\Menu::where('name', 'admin')->first();
        if ($menu) {
            \TCG\Voyager\Models\MenuItem::firstOrCreate(
                ['menu_id' => $menu->id, 'title' => 'Signature Settings'],
                [
                    'url'        => '/admin/settings#email-signatures',
                    'target'     => '_self',
                    'icon_class' => 'voyager-settings',
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => 100,
                ]
            );
        }

        $this->command->info('Email Signature settings seeded. Visit /admin/settings#email-signatures to manage allowed senders.');
    }
}
