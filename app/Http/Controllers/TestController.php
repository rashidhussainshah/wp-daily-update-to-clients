<?php

namespace App\Http\Controllers;

use App\Mail\UserLogin;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testEmail($emailName = '', $toEmail = 'developer@webpenter.com', $sendEmail = true)
    {
//        dd(setting('user-confirmation-email.user_registration_confirmation_email_logo'));
        $data = [
            'appName' => 'Portal',
            'siteUrl' => 'http://ps.loal/',
            'email_user_full_name' => 'Test User',
        ];
        $view = null;
        $mail = null;
        $mailMsg = '';

        try {
            if ($emailName == 'user_login') {
                $data = [
                    'mail_name' => 'SignupSuccess',
                    'to' => $toEmail,
                    'password' => 'password',
                    'site_url' => config('app.url'),
                    'login_link' => config('app.url') . '/login',
                    'loginLink' => config('app.url') . '/login',
                    'no_reply' => false,
                ];
                if ($sendEmail) {
                    $mail = new UserLogin($data);
                    $mailMsg = 'VisitorEmailConfirmation email sent';
                } else {
                    return view('emails.user_login', [
                        'site_url' => config('app.url'),
                        'loginLink' => config('app.url') . '/admin/login',
                        'user' => User::first()
                    ]);
                }
            } else {
                $view = 'emails.' . $emailName;
            }

            if ($sendEmail && $toEmail != '' && !is_null($mail)) {
                \Mail::to($toEmail)->send($mail);
                return $mailMsg;
            } elseif (!is_null($view)) {
                return view($view, $data);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
