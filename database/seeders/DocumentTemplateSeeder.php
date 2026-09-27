<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

/**
 * Three starter letter templates so there's something to issue immediately.
 * These are examples, not a fixed set - HR can edit these or add a
 * completely new category from the builder UI with zero code changes.
 * Idempotent by name - safe to re-run.
 */
class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'hr.document_company_name', 'display_name' => 'HR Document Company Name', 'value' => 'WebPenter', 'type' => 'text', 'order' => 1],
            // Two independent signature slots - each is either this typed
            // name+title (default) or an uploaded signature image set via
            // the *_image setting below, which wins over the typed name
            // when present. Lets e.g. the CEO's slot use a scanned
            // signature while the Founder's stays typed text, or vice
            // versa - configured here in Voyager Settings, not per-letter.
            ['key' => 'hr.document_signatory_1_name', 'display_name' => 'Signatory 1 Name', 'value' => 'Rashid Bukhari', 'type' => 'text', 'order' => 2],
            ['key' => 'hr.document_signatory_1_title', 'display_name' => 'Signatory 1 Title', 'value' => 'Chief Executive Officer', 'type' => 'text', 'order' => 3],
            ['key' => 'hr.document_signatory_1_image', 'display_name' => 'Signatory 1 Signature Image (optional - overrides typed name above)', 'value' => '', 'type' => 'image', 'order' => 4],
            ['key' => 'hr.document_signatory_2_name', 'display_name' => 'Signatory 2 Name', 'value' => 'Zahid Khurshid', 'type' => 'text', 'order' => 5],
            ['key' => 'hr.document_signatory_2_title', 'display_name' => 'Signatory 2 Title', 'value' => 'Founder', 'type' => 'text', 'order' => 6],
            ['key' => 'hr.document_signatory_2_image', 'display_name' => 'Signatory 2 Signature Image (optional - overrides typed name above)', 'value' => '', 'type' => 'image', 'order' => 7],
        ];

        foreach ($settings as $s) {
            // Only set if missing - never clobber a value already changed
            // via Voyager Settings by re-running this seeder.
            if (!Setting::where('key', $s['key'])->exists()) {
                Setting::create(['display_name' => $s['display_name'], 'key' => $s['key'], 'value' => $s['value'], 'type' => $s['type'], 'order' => $s['order'], 'group' => 'HR Documents']);
            }
        }

        $templates = [
            [
                'name' => 'Experience Letter',
                'category' => 'experience_letter',
                'design' => DocumentTemplate::DESIGN_CLASSIC,
                'body' => <<<'HTML'
                <p>Date: {{today}}<br>Ref: {{reference_number}}</p>
                <p><strong>TO WHOM IT MAY CONCERN</strong></p>
                <p>This is to certify that <strong>{{employee_name}}</strong> is currently employed at {{company_name}} as <strong>{{designation}}</strong>, having joined the organization on {{join_date}}.</p>
                <p>During this period, {{employee_name}} has been a dedicated and valuable member of our team, and has shown professionalism and commitment in their role.</p>
                <p>This letter is issued upon the employee's request {{purpose}}.</p>
                <p>We wish {{employee_name}} continued success.</p>
                <p>Sincerely,</p>
                HTML,
                'is_active' => true,
            ],
            [
                'name' => 'Relieving Letter',
                'category' => 'relieving_letter',
                'design' => DocumentTemplate::DESIGN_CLASSIC,
                'body' => <<<'HTML'
                <p>Date: {{today}}<br>Ref: {{reference_number}}</p>
                <p><strong>TO WHOM IT MAY CONCERN</strong></p>
                <p>This is to certify that <strong>{{employee_name}}</strong> was employed at {{company_name}} as <strong>{{designation}}</strong> from {{join_date}} to {{last_working_day}}.</p>
                <p>{{employee_name}} has been relieved of all duties and responsibilities as of {{last_working_day}}, following resignation/completion of engagement. All dues, if any, have been settled as per company policy.</p>
                <p>We thank {{employee_name}} for their contributions and wish them well in future endeavors.</p>
                <p>Sincerely,</p>
                HTML,
                'is_active' => true,
            ],
            [
                'name' => 'Internship Completion Letter',
                'category' => 'internship_letter',
                'design' => DocumentTemplate::DESIGN_MODERN,
                'body' => <<<'HTML'
                <p>Date: {{today}}<br>Ref: {{reference_number}}</p>
                <p><strong>TO WHOM IT MAY CONCERN</strong></p>
                <p>This is to certify that <strong>{{employee_name}}</strong> successfully completed an internship at {{company_name}} in the role of <strong>{{designation}}</strong>, from {{join_date}} to {{last_working_day}}.</p>
                <p>During the internship, {{employee_name}} worked on real projects, gained hands-on experience, and demonstrated a strong willingness to learn.</p>
                <p>We wish {{employee_name}} the very best in their future career.</p>
                <p>Sincerely,</p>
                HTML,
                'is_active' => true,
            ],
        ];

        foreach ($templates as $t) {
            $template = DocumentTemplate::firstOrCreate(['name' => $t['name']], $t);
            $this->command?->info(($template->wasRecentlyCreated ? 'Created' : 'Already exists') . ": {$template->name}");
        }
    }
}
