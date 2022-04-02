<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EndOfDayReport extends Mailable
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Daily Report '. readableCurrentDate())->view('email_templates.end_of_day_report');
    }
}
