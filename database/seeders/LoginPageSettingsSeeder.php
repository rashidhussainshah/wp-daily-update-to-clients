<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class LoginPageSettingsSeeder extends Seeder
{
    public function run()
    {
        $setting = $this->findSetting('login.video_url');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Login Page Video URL',
                'value'        => 'https://youtube.com/watch?v=K1jmGH7TshQ',
                'details'      => 'YouTube link shown as a background video on the admin login page (http://p.test/admin/login). Leave blank to fall back to the plain background image.',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Login Page',
            ])->save();
        }
    }

    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
