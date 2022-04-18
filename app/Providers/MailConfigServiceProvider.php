<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $mail = DB::table('mails')->first();
        if (true) //checking if table is not empty
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
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
