<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class CheckinSettingsSeeder extends Seeder
{
    public function run()
    {
        $setting = $this->findSetting('checkin.default_checkin_time');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Default Check-in Time (Mon-Fri)',
                'value'        => '10:30',
                'details'      => 'Default allowed check-in time for weekdays. Overridden per-user via checkin_time column.',
                'type'         => 'text',
                'order'        => 10,
                'group'        => 'Checkin',
            ])->save();
        }

        $setting = $this->findSetting('checkin.saturday_checkin_time');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Saturday Check-in Time',
                'value'        => '10:30',
                'details'      => 'Allowed check-in time on Saturdays. Applies to all users including night-shift workers.',
                'type'         => 'text',
                'order'        => 11,
                'group'        => 'Checkin',
            ])->save();
        }
    }

    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
