<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class UserLogin extends BaseEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $loginLink;
    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);

        if (isset($data['login_link'])) {
            $this->loginLink = $data['login_link'];
        } else {
            $this->loginLink = $this->siteUrl . '/admin/login';
        }
        $this->user = $data['to'] ? User::where('email',  $data['to'])->first() : '';
        $this->password = $data['password'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sign up successful')->replyTo($this->reply_to)
            ->view('emails.user_login');
    }
}
