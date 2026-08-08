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
            // A hung/unreachable SMTP connection with no timeout can block the
            // PHP process indefinitely — each cron tick that hits it spawns
            // another stuck process, piling up against the account's process
            // limit. 20s is generous for a normal SMTP handshake but fails fast
            // if the server is actually unreachable.
            'timeout'    => 20,
            'auth_mode'  => null,
        ]);

        return Mail::mailer($key);
    }
}
