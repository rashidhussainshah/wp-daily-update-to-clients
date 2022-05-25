<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EndOfDayReport extends BaseEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var string */
    public $name;

    /** @var string */
    public $eodHtmlTemplate;

    /** @var string */
    public $subject;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->eodHtmlTemplate = $data['dynamic_eod_content'];
        $this->subject = $data['subject'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Daily Report '. readableCurrentDate())
                ->view('email_templates.end_of_day_report');
    }
}
