<?php

namespace App\Mail;

use App\Models\DocumentIssuance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

/**
 * Emails an issued HR letter (experience/relieving/internship/etc.) to its
 * recipient - reuses the same SMTP-account infra as the marketing
 * campaigns feature (CampaignMailerTrait::getMailer) rather than
 * configuring a mailer here.
 */
class DocumentIssuanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DocumentIssuance $issuance)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->issuance->template->name} - {$this->issuance->recipient_name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.document-issuance',
            with: ['issuance' => $this->issuance],
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        if (!$this->issuance->pdf_path) {
            return [];
        }

        $path = storage_path('app/public/' . $this->issuance->pdf_path);

        if (!file_exists($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as(Str::slug($this->issuance->template->name) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
