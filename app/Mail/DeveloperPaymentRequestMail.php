<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class DeveloperPaymentRequestMail extends BaseEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var mixed
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['subject'] ?? '')
            ->view('email_templates.payment_request_for_management');
    }
}
