<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MarketingCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientName;
    public string $htmlBody;
    public string $textBody;
    public string $campaignSubject;
    public string $campaignFromName;
    public string $campaignFromEmail;
    public string $campaignReplyTo;

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
        Config::set('mail.mailers.smtp', [
            'transport'  => 'smtp',
            'host'       => setting('marketing.smtp_host')       ?: 'smtp.titan.email',
            'port'       => (int) (setting('marketing.smtp_port') ?: 465),
            'encryption' => setting('marketing.smtp_encryption') ?: 'ssl',
            'username'   => setting('marketing.smtp_username')   ?: '',
            'password'   => setting('marketing.smtp_password')   ?: '',
            'timeout'    => null,
            'auth_mode'  => null,
        ]);

        $this->recipientName   = $recipientName;
        $this->htmlBody        = $this->personalise($campaign->html_body, $recipientName);
        $this->textBody        = $this->personalise($campaign->text_body ?? '', $recipientName);
        $this->campaignSubject = $this->personalise($campaign->subject, $recipientName);

        $this->campaignFromEmail = setting('marketing.from_email') ?: 'contact@webpenter.com';
        $this->campaignFromName  = setting('marketing.from_name')  ?: 'Webpenter';
        $this->campaignReplyTo   = setting('marketing.reply_to')   ?: 'contact@webpenter.com';

        $this->companyName     = setting('marketing.company_name')     ?: 'Webpenter';
        $this->companyTagline  = setting('marketing.company_tagline')  ?: 'Software & Development';
        $this->companyAddress  = setting('marketing.company_address')  ?: 'Pakistan';
        $this->companyWebsite  = setting('marketing.company_website')  ?: 'https://webpenter.com';
        $this->companyEmail    = setting('marketing.from_email')       ?: 'contact@webpenter.com';
        $this->companyPhone    = setting('marketing.company_phone')    ?: '';
        $this->companyLogoUrl  = setting('marketing.company_logo_url') ?: '';
        $this->unsubscribeText = setting('marketing.unsubscribe_text')
            ?: 'You received this because you are registered as a Homey theme user. Reply "unsubscribe" to opt out.';
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
}
