<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class MarketingSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Sender identity ─────────────────────────────────────────────
            ['key' => 'marketing.from_name',       'display_name' => 'From Name',           'value' => 'Rashid | Webpenter',         'type' => 'text',     'order' => 1],
            ['key' => 'marketing.from_email',      'display_name' => 'From Email',          'value' => 'sales@webpenter.com',        'type' => 'text',     'order' => 2],
            ['key' => 'marketing.reply_to',        'display_name' => 'Reply-To Email',      'value' => 'sales@webpenter.com',        'type' => 'text',     'order' => 3],

            // ── SMTP override for marketing (leave blank to use app default) ─
            ['key' => 'marketing.smtp_host',       'display_name' => 'SMTP Host',           'value' => '',                           'type' => 'text',     'order' => 4],
            ['key' => 'marketing.smtp_port',       'display_name' => 'SMTP Port',           'value' => '',                           'type' => 'text',     'order' => 5],
            ['key' => 'marketing.smtp_username',   'display_name' => 'SMTP Username',       'value' => '',                           'type' => 'text',     'order' => 6],
            ['key' => 'marketing.smtp_password',   'display_name' => 'SMTP Password',       'value' => '',                           'type' => 'password', 'order' => 7],
            ['key' => 'marketing.smtp_encryption', 'display_name' => 'SMTP Encryption',     'value' => 'tls',                        'type' => 'text',     'order' => 8],

            // ── Sending throttle ────────────────────────────────────────────
            ['key' => 'marketing.batch_size',      'display_name' => 'Batch Size',          'value' => '200',                        'type' => 'text',     'order' => 9],
            ['key' => 'marketing.delay_ms',        'display_name' => 'Delay Between Emails (ms)', 'value' => '100',                  'type' => 'text',     'order' => 10],

            // ── Company footer info ─────────────────────────────────────────
            ['key' => 'marketing.company_name',    'display_name' => 'Company Name',        'value' => 'Webpenter',                  'type' => 'text',     'order' => 11],
            ['key' => 'marketing.company_tagline', 'display_name' => 'Company Tagline',     'value' => 'Software & Development',     'type' => 'text',     'order' => 12],
            ['key' => 'marketing.company_address', 'display_name' => 'Company Address',     'value' => 'Rahim Yar Khan, Punjab, Pakistan', 'type' => 'text','order' => 13],
            ['key' => 'marketing.company_website', 'display_name' => 'Company Website',     'value' => 'https://webpenter.com',      'type' => 'text',     'order' => 14],
            ['key' => 'marketing.company_phone',   'display_name' => 'Company Phone',       'value' => '+923094742343',              'type' => 'text',     'order' => 15],
            ['key' => 'marketing.company_logo_url','display_name' => 'Logo URL (email header)', 'value' => '',                       'type' => 'text',     'order' => 16],

            // ── Unsubscribe ─────────────────────────────────────────────────
            ['key' => 'marketing.unsubscribe_text','display_name' => 'Unsubscribe Footer Text', 'value' => 'You received this because you are registered as a Homey theme user. To unsubscribe, reply with "unsubscribe".', 'type' => 'text_area', 'order' => 17],
        ];

        foreach ($settings as $data) {
            $setting = Setting::firstOrNew(['key' => $data['key']]);
            if (!$setting->exists) {
                $setting->fill([
                    'display_name' => $data['display_name'],
                    'value'        => $data['value'],
                    'details'      => '',
                    'type'         => $data['type'],
                    'order'        => $data['order'],
                    'group'        => 'Marketing',
                ])->save();
            }
        }
    }
}
