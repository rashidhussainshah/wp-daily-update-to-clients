<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
                'transport'     => setting("email-configuration.driver"),
                'host'       => setting('email-configuration.host'),
                'port'       => setting('email-configuration.port'),
                'encryption' => setting('email-configuration.encryption'),
                'username'   => setting('email-configuration.username'),
                'password'   => setting('email-configuration.password'),
                'timeout' => null,
                'auth_mode' => null
            );

            $mailFromConfig = array(
                'address'     => setting('email-configuration.from') ?? env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name'       =>  setting('email-configuration.from.name') ?? env('MAIL_FROM_NAME', 'Example'),
            );

//        Log::info('=== before ===');
//        Log::info(Config::get('mail.from'));
        Config::set('mail.from', $mailFromConfig);
        Config::set('mail.mailers.smtp', $mailConfig);
//        Log::info('=== after ===');
//        Log::info(Config::get('mail.from'));
    }

}
