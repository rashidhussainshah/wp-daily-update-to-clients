<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

/**
 * The two names/titles that sign every certificate - editable in Voyager
 * (Settings -> Certification) rather than hardcoded in the template, per
 * A4/A9. Also the per-signatory enable/disable toggles: Marketing sometimes
 * needs a certificate WITHOUT a signature for social media, and WITH one
 * for a physical handover - these are the settings-level defaults; the
 * marketing dashboard's per-download buttons can override them for one PDF
 * without changing the default. Idempotent - safe to re-run (won't
 * overwrite a value already changed via Voyager Settings, since
 * updateOrCreate only touches the row if it doesn't exist... actually it
 * always overwrites both, so this really only matters for setting the
 * initial values on a fresh install).
 */
class AcademyCertificateSignatorySeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'academy.certificate_signatory_1_name', 'display_name' => 'Signatory 1 Name', 'value' => 'Rashid Bukhari', 'order' => 5, 'group' => 'Certification', 'type' => 'text'],
            ['key' => 'academy.certificate_signatory_1_title', 'display_name' => 'Signatory 1 Title', 'value' => 'Chief Executive Officer', 'order' => 6, 'group' => 'Certification', 'type' => 'text'],
            // Off by default - only the Founder's signature shows out of
            // the box; the CEO's can be switched on later from Voyager
            // Settings whenever that's wanted.
            ['key' => 'academy.certificate_signatory_1_enabled', 'display_name' => 'Signatory 1 Shown by Default', 'value' => '0', 'order' => 7, 'group' => 'Certification', 'type' => 'checkbox'],
            // Uploaded here wins over the typed name above - same
            // image-overrides-text convention as the HR document builder's
            // signatories (hr.document_signatory_*_image).
            ['key' => 'academy.certificate_signatory_1_image', 'display_name' => 'Signatory 1 Signature Image (optional - overrides typed name above)', 'value' => '', 'order' => 7, 'group' => 'Certification', 'type' => 'image'],
            ['key' => 'academy.certificate_signatory_2_name', 'display_name' => 'Signatory 2 Name', 'value' => 'Zahid Khurshid', 'order' => 8, 'group' => 'Certification', 'type' => 'text'],
            ['key' => 'academy.certificate_signatory_2_title', 'display_name' => 'Signatory 2 Title', 'value' => 'Founder', 'order' => 9, 'group' => 'Certification', 'type' => 'text'],
            ['key' => 'academy.certificate_signatory_2_enabled', 'display_name' => 'Signatory 2 Shown by Default', 'value' => '1', 'order' => 10, 'group' => 'Certification', 'type' => 'checkbox'],
            ['key' => 'academy.certificate_signatory_2_image', 'display_name' => 'Signatory 2 Signature Image (optional - overrides typed name above)', 'value' => '', 'order' => 10, 'group' => 'Certification', 'type' => 'image'],
            ['key' => 'academy.social_hashtags', 'display_name' => 'Academy Social Media Standard Hashtags', 'value' => '#WebPenter #WebPenterAcademy #WebDevelopment #MobileAppDevelopment #SoftwareDevelopment #BookingAndRental #RealEstateSoftware', 'order' => 9, 'group' => 'Academy', 'type' => 'text'],
        ];

        foreach ($settings as $s) {
            // Only set if missing - never clobber a value already changed
            // via Voyager Settings by re-running this seeder.
            if (!Setting::where('key', $s['key'])->exists()) {
                Setting::create(['display_name' => $s['display_name'], 'key' => $s['key'], 'value' => $s['value'], 'type' => $s['type'], 'order' => $s['order'], 'group' => $s['group']]);
                $this->command?->info("Set {$s['key']} = {$s['value']}");
            }
        }

        // Re-group the pre-existing signatory settings into their own
        // "Certification" tab - purely cosmetic (doesn't touch the value),
        // safe to run every time.
        Setting::whereIn('key', [
            'academy.certificate_signatory_1_name',
            'academy.certificate_signatory_1_title',
            'academy.certificate_signatory_2_name',
            'academy.certificate_signatory_2_title',
        ])->update(['group' => 'Certification']);
    }
}
