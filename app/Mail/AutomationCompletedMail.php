<?php

namespace App\Mail;

use App\Models\CampaignAutomation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AutomationCompletedMail extends Mailable
{
    public function __construct(
        public CampaignAutomation $automation,
        public int $totalSent,
        public int $totalFailed,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Automation Complete: ' . $this->automation->name);
    }

    public function content(): Content
    {
        return new Content(view: 'vendor.voyager.campaign-automations.mail.completed');
    }
}
