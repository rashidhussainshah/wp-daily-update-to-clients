<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $setting = $this->findSetting('site.title');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.title'),
                'value'        => __('voyager::seeders.settings.site.title'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('site.description');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.description'),
                'value'        => __('voyager::seeders.settings.site.description'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('site.logo');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.logo'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 3,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('site.google_analytics_tracking_id');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.google_analytics_tracking_id'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 4,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('admin.bg_image');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.background_image'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 5,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.title');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.title'),
                'value'        => 'Voyager',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.description');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.description'),
                'value'        => __('voyager::seeders.settings.admin.description_value'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.loader');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.loader'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 3,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.icon_image');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.icon_image'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 4,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.google_analytics_client_id');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.google_analytics_client_id'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Admin',
            ])->save();
        }

        // Contact Message Settings
        $setting = $this->findSetting('contact.email_notification_enabled');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Enable Email Notifications',
                'value'        => '0',
                'details'      => json_encode([
                    'on' => 'Enabled',
                    'off' => 'Disabled',
                    'checked' => false,
                ]),
                'type'         => 'checkbox',
                'order'        => 1,
                'group'        => 'Contact',
            ])->save();
        }

        $setting = $this->findSetting('contact.notification_email');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Notification Email(s)',
                'value'        => 'contact@webpenter.com,ahmad.nawaz@webpenter.com',
                'details'      => 'Email addresses to receive contact message notifications (comma-separated for multiple emails)',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Contact',
            ])->save();
        }

        $setting = $this->findSetting('contact.slack_notification_enabled');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Enable Slack Notifications',
                'value'        => '0',
                'details'      => json_encode([
                    'on' => 'Enabled',
                    'off' => 'Disabled',
                    'checked' => false,
                ]),
                'type'         => 'checkbox',
                'order'        => 3,
                'group'        => 'Contact',
            ])->save();
        }

        $setting = $this->findSetting('contact.slack_webhook_url');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => 'Slack Webhook URL',
                'value'        => 'https://hooks.slack.com/services/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',
                'details'      => 'Slack webhook URL for contact message notifications',
                'type'         => 'text',
                'order'        => 4,
                'group'        => 'Contact',
            ])->save();
        }
    }

    /**
     * [setting description].
     *
     * @param [type] $key [description]
     *
     * @return [type] [description]
     */
    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
