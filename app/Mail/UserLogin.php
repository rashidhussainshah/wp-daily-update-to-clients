<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class UserLogin extends BaseEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $password;
    /**
     * @var mixed
     */
    public $name;
    /**
     * @var mixed
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);

        $this->name = $data['name'];
        $this->email = $data['to'];
        $this->password = $data['password'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): UserLogin
    {
        $replyTo = setting('user-confirmation-email.user_confirmation_reply_to_email');
        if ($replyTo) {
            return $this->subject(setting('user-confirmation-email.email_subject'))
                ->replyTo(setting('user-confirmation-email.user_confirmation_reply_to_email'))
                ->view('emails.user_login');
        } else {
            return $this->subject(setting('user-confirmation-email.email_subject'))
                ->view('emails.user_login');
        }
    }
}
