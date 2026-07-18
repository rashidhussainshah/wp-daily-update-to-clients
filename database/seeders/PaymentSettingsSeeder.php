<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class PaymentSettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'payments.max_conversion_rate'],
            [
                'display_name' => 'Max Currency Conversion Rate (USD to PKR)',
                'value'        => '290',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Payments',
            ]
        );

    }
}
