<?php

namespace App\Utils\Traits;

use App\Models\SmtpAccount;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

trait CampaignMailerTrait
{
    /**
     * Returns a mailer configured for the given SMTP account.
     * If null or inactive, falls back to the default .env mailer.
     * Never modifies the existing 'smtp' mailer — creates a new named one.
     */
    protected function getMailer(?SmtpAccount $smtp): \Illuminate\Mail\Mailer
    {
        if (!$smtp || !$smtp->is_active) {
            return Mail::mailer(config('mail.default'));
        }

        $key = 'acct_' . $smtp->id;

        Config::set("mail.mailers.{$key}", [
            'transport'  => 'smtp',
            'host'       => $smtp->host,
            'port'       => (int) $smtp->port,
            'encryption' => $smtp->encryption,
            'username'   => $smtp->username,
            'password'   => $smtp->decrypted_password,
            'timeout'    => null,
            'auth_mode'  => null,
        ]);

        return Mail::mailer($key);
    }
}
