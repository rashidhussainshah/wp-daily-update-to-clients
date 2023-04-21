<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class BaseEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = null)
    {
            $mailConfig = array(
                'transport'  => setting("email-configuration.driver") ?? 'smtp',
                'host'       => setting('email-configuration.host') ?? env('MAIL_HOST', 'smtp.mailgun.org'),
                'port'       => setting('email-configuration.port') ?? env('MAIL_PORT', 587),
                'encryption' => setting('email-configuration.encryption') ?? env('MAIL_ENCRYPTION', 'tls'),
                'username'   => setting('email-configuration.username') ?? env('MAIL_USERNAME'),
                'password'   => setting('email-configuration.password') ?? env('MAIL_PASSWORD'),
                'timeout' => null,
                'auth_mode' => null
            );

            $mailFromConfig = array(
                'address'     => setting('email-configuration.from') ?? env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name'       =>  setting('email-configuration.from.name') ?? env('MAIL_FROM_NAME', 'Example'),
            );
//        Config::set('mail.from', $mailFromConfig);
          Config::set('mail.mailers.smtp', $mailConfig);
    }

}
