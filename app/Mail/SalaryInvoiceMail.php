<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SalaryInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;
    public $invoicePath;

    /**
     * Create a new message instance.
     *
     * @param array $invoiceData Salary calculation data
     * @param string|null $invoicePath Path to PDF invoice (optional)
     */
    public function __construct(array $invoiceData, ?string $invoicePath = null)
    {
        $this->invoiceData = $invoiceData;
        $this->invoicePath = $invoicePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $userName = $this->invoiceData['user']['name'];
        $month = \Carbon\Carbon::createFromFormat('Y-m', $this->invoiceData['month'])->format('F Y');

        return new Envelope(
            subject: "Salary Invoice - {$month} - {$userName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.salary-invoice',
            with: [
                'invoiceData' => $this->invoiceData,
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
        if ($this->invoicePath && file_exists($this->invoicePath)) {
            // Create clean filename with user name
            $userName = str_replace(' ', '_', $this->invoiceData['user']['name']);
            $fileName = "salary_invoice_{$userName}_{$this->invoiceData['month']}.pdf";

            return [
                Attachment::fromPath($this->invoicePath)
                    ->as($fileName)
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}
