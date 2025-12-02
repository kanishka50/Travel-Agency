<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TourReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $recipientType; // 'tourist' or 'guide'

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $recipientType = 'tourist')
    {
        $this->booking = $booking;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'tourist'
            ? 'Your Tour Starts in 7 Days!'
            : 'Upcoming Tour Reminder - ' . $this->booking->booking_number;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tour-reminder',
            with: [
                'booking' => $this->booking,
                'recipientType' => $this->recipientType,
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
