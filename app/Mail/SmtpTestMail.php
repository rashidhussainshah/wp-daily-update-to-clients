<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class SmtpTestMail extends Mailable
{
    public function __construct(
        private string $fromAddress,
        private string $fromName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromAddress, $this->fromName),
            subject: 'SMTP Test — ' . $this->fromAddress,
        );
    }

    public function content(): Content
    {
        return new Content(htmlString:
            '<p>This is a test email from <strong>' . e($this->fromAddress) . '</strong>.</p>'
            . '<p>SMTP is configured correctly.</p>'
        );
    }
}
