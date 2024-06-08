<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class EndOfDayReport extends BaseEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var string */
    public $name;

    /** @var string */
    public $eodHtmlTemplate;

    /** @var string */
    public $subject;
    /**
     * @var mixed
     */
    public $developerName;
    /**
     * @var mixed
     */
    public $clientName;
    /**
     * @var mixed
     */
    public $projectName;
    /**
     * @var mixed
     */
    public $signature;
    /**
     * @var mixed
     */
    public $planForTomorrow;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->eodHtmlTemplate = $data['dynamic_eod_content'];
        $this->subject = $data['subject'];
        $this->developerName = $data['developer_name'];
        $this->clientName = $data['client_name'];
        $this->projectName = $data['project_name'];
        $this->signature = $data['signature'];
        $this->planForTomorrow = $data['plan_for_tomorrow'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Daily Report '. readableCurrentDate())
                ->view('email_templates.end_of_day_upgraded_design');
    }
}
