<?php

namespace App\Mail;

use App\Models\GuideRegistrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuideRegistrationAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $registrationRequest;

    public function __construct(GuideRegistrationRequest $registrationRequest)
    {
        $this->registrationRequest = $registrationRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Guide Registration Request',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.guide-registration-admin-notification',
        );
    }
}