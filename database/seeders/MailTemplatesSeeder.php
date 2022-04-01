<?php

namespace Database\Seeders;

use App\Mail\EodMail;
use Illuminate\Database\Seeder;
use Spatie\MailTemplates\Models\MailTemplate;

class MailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'Rashid';
        MailTemplate::create([
            'mailable' => EodMail::class,
            'subject' => 'Welcome, {{ name }}',
            'html_template' => '<h1>Hello, {{ name }}!</h1>',
            'text_template' => 'Hello, {{ name }}!',
        ]);
    }
}
