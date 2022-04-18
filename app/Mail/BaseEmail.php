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
//            $config = array(
//                'driver'     => setting("email-configuration.driver"),
//                'host'       => setting('email-configuration.host'),
//                'port'       => setting('email-configuration.port'),
//                'from'       => array('address' => setting('email-configuration.from'), 'name' => setting('email-configuration.from.name')),
//                'encryption' => setting('email-configuration.encryption'),
//                'username'   => setting('email-configuration.username'),
//                'password'   => setting('email-configuration.password'),
//                'sendmail'   => '/usr/sbin/sendmail -bs',
//                'pretend'    => false,
//            );
//            Config::set('mail', $config);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
