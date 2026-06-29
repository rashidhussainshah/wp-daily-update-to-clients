<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\EmailSignature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
        $this->recipientName   = $recipientName;
        $this->campaignSubject = $this->personalise($campaign->subject, $recipientName);

        $body = $this->sanitiseUrls($this->personalise($campaign->html_body, $recipientName));

        $signature = EmailSignature::where('sender_email', $campaign->from_email)
            ->where('is_active', true)
            ->first();
        if ($signature) {
            $body .= $signature->renderHtml();
        }

        $this->htmlBody = $body;
        $this->textBody = $this->personalise($campaign->text_body ?? '', $recipientName);

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
        $replyTo = $this->campaignReplyTo;

        return new Envelope(
            from: new Address($this->campaignFromEmail, $this->campaignFromName),
            replyTo: [new Address($replyTo)],
            subject: $this->campaignSubject,
            // using: [
            //     function (\Symfony\Component\Mime\Email $message) use ($replyTo) {
            //         $headers = $message->getHeaders();
            //         $headers->addTextHeader(
            //             'List-Unsubscribe',
            //             '<mailto:' . $replyTo . '?subject=unsubscribe>'
            //         );
            //         $headers->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
            //         $headers->addTextHeader('Precedence', 'bulk');
            //         $headers->addTextHeader('X-Mailer', 'Webpenter Mailer 1.0');
            //     },
            // ]
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

    // Strip ?subject=... from mailto: links — triggers "Malicious URL" SMTP rejection
    private function sanitiseUrls(string $html): string
    {
        return preg_replace('/mailto:([^"\'>\s]+)\?subject=[^"\'>\s]*/i', 'mailto:$1', $html);
    }
}
