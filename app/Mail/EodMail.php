<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class EodMail extends TemplateMailable
{
    use Queueable, SerializesModels;

    /** @var string */
    public $name;

    /** @var string */
    public $eodHtmlTemplate;

    public function __construct(Project $project, $eodHtmlTemplate)
    {
        $this->name = $project->eodConfiguration->client->name;
        $this->eodHtmlTemplate = $eodHtmlTemplate;
    }

    public function getHtmlLayout(): string
    {
        /**
         * In your application you might want to fetch the layout from an external file or Blade view.
         *
         * External file: `return file_get_contents(storage_path('mail-layouts/main.html'));`
         *
         * Blade view: `return view('mailLayouts.main', $data)->render();`
         */

        return view('mail-layouts.eod')->render();

//        return '<header>Site name!</header>{{{ body }}}<footer>Copyright 2018</footer>';
    }

}
