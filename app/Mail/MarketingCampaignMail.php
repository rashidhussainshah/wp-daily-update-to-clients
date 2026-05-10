<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MarketingCampaignMail extends BaseEmail
{
    // Queueable + SerializesModels are inherited from BaseEmail — no duplicates here.
    // No ShouldQueue — emails are sent synchronously (dispatchSync in controller).

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
        // BaseEmail reads email-configuration.* from Voyager settings (with .env fallback)
        // and calls Config::set('mail.mailers.smtp', ...) — same SMTP used by payment emails.
        parent::__construct();

        $this->recipientName   = $recipientName;
        $this->htmlBody        = $this->personalise($campaign->html_body, $recipientName);
        $this->textBody        = $this->personalise($campaign->text_body ?? '', $recipientName);
        $this->campaignSubject = $this->personalise($campaign->subject, $recipientName);
        $this->campaignReplyTo = setting('email-configuration.from') ?: $campaign->from_email;

        // Always use the working default sender (webpenterinvoices@gmail.com).
        // Ignore marketing.smtp_* settings entirely.
        $this->campaignFromEmail = setting('email-configuration.from')
            ?: config('mail.from.address', $campaign->from_email);
        $this->campaignFromName  = config('mail.from.name', $campaign->from_name);

        $this->companyName     = setting('marketing.company_name')     ?: 'Webpenter';
        $this->companyTagline  = setting('marketing.company_tagline')  ?: 'Software & Development';
        $this->companyAddress  = setting('marketing.company_address')  ?: 'Pakistan';
        $this->companyWebsite  = setting('marketing.company_website')  ?: 'https://webpenter.com';
        $this->companyEmail    = setting('email-configuration.from')   ?: 'sales@webpenter.com';
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
