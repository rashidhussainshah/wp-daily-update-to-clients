<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MarketingCampaignMail extends BaseEmail
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $htmlBody;
    public string $textBody;
    public string $campaignSubject;
    public string $campaignFromName;
    public string $campaignFromEmail;
    public string $campaignReplyTo;

    // Footer vars pulled from Voyager settings — passed to blade view
    public string $companyName;
    public string $companyTagline;
    public string $companyAddress;
    public string $companyWebsite;
    public string $companyEmail;
    public string $companyPhone;
    public string $companyLogoUrl;
    public string $unsubscribeText;

    public function __construct(EmailCampaign $campaign, string $recipientName)
    {
        parent::__construct(); // load email-configuration.* SMTP (same as DeveloperPaymentMail)

        $this->recipientName     = $recipientName;
        $this->htmlBody          = $this->personalise($campaign->html_body, $recipientName);
        $this->textBody          = $this->personalise($campaign->text_body ?? '', $recipientName);
        $this->campaignSubject   = $this->personalise($campaign->subject, $recipientName);
        $this->campaignFromName  = $campaign->from_name;
        $this->campaignFromEmail = $campaign->from_email;
        $this->campaignReplyTo   = setting('marketing.reply_to') ?: $campaign->from_email;

        $this->companyName     = setting('marketing.company_name')     ?: 'Webpenter';
        $this->companyTagline  = setting('marketing.company_tagline')  ?: 'Software & Development';
        $this->companyAddress  = setting('marketing.company_address')  ?: 'Pakistan';
        $this->companyWebsite  = setting('marketing.company_website')  ?: 'https://webpenter.com';
        $this->companyEmail    = setting('marketing.from_email')       ?: 'sales@webpenter.com';
        $this->companyPhone    = setting('marketing.company_phone')    ?: '';
        $this->companyLogoUrl  = setting('marketing.company_logo_url') ?: '';
        $this->unsubscribeText = setting('marketing.unsubscribe_text') ?: 'Reply "unsubscribe" to opt out.';

        $this->applySmtpOverride($campaign);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->campaignFromEmail, $this->campaignFromName),
            replyTo: [new Address($this->campaignReplyTo)],
            subject: $this->campaignSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing-campaign',
            text: 'emails.marketing-campaign-text',
        );
    }

    private function personalise(string $template, string $name): string
    {
        $firstName = explode(' ', trim($name))[0] ?: 'there';
        return str_replace(
            ['{{name}}', '{{first_name}}'],
            [$name ?: 'there', $firstName],
            $template
        );
    }

    private function applySmtpOverride(EmailCampaign $campaign): void
    {
        $host = setting('marketing.smtp_host');

        if (!$host) {
            // No dedicated marketing SMTP — BaseEmail already configured the
            // production SMTP via email-configuration.* settings.
            // Align the envelope from-address with the same settings so
            // Gmail/SMTP accepts the sender identity.
            $this->campaignFromEmail = setting('email-configuration.from')
                ?: config('mail.from.address')
                ?: $campaign->from_email;
            $this->campaignFromName  = setting('email-configuration.from.name')
                ?: config('mail.from.name')
                ?: $campaign->from_name;
            return;
        }

        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => $host,
            'port'       => setting('marketing.smtp_port') ?: 587,
            'encryption' => setting('marketing.smtp_encryption') ?: 'tls',
            'username'   => setting('marketing.smtp_username'),
            'password'   => setting('marketing.smtp_password'),
            'timeout'    => null,
        ]);

        Config::set('mail.from', [
            'address' => $campaign->from_email,
            'name'    => $campaign->from_name,
        ]);
    }
}
