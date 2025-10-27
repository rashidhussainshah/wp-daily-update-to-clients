<?php

namespace App\Mail;

use App\Models\Fine;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FineNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fine;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Fine $fine
     */
    public function __construct(Fine $fine)
    {
        $this->fine = $fine;
        $this->user = $fine->user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $date = \Carbon\Carbon::parse($this->fine->date)->format('F d, Y');

        return new Envelope(
            subject: "Important: Fine Notice - {$date}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fine-notification',
            with: [
                'fine' => $this->fine,
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
