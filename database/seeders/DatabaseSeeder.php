<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MailTemplatesSeeder::class,
            RolesTableSeeder::class,
            MarketingSettingsSeeder::class,
            EmailCampaignSeeder::class,
            EmailCampaignMenuSeeder::class,
        ]);
    }
}
