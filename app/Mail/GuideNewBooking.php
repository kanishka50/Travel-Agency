<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuideNewBooking extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Received - ' . $this->booking->booking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Determine tour title based on booking type
        if ($this->booking->booking_type === 'custom_request' && $this->booking->touristRequest) {
            $tourTitle = $this->booking->touristRequest->title . ' (Custom Tour)';
        } elseif ($this->booking->guidePlan) {
            $tourTitle = $this->booking->guidePlan->title;
        } else {
            $tourTitle = 'Tour Booking #' . $this->booking->booking_number;
        }

        return new Content(
            markdown: 'emails.guide-new-booking',
            with: [
                'booking' => $this->booking,
                'tourist' => $this->booking->tourist,
                'guide' => $this->booking->guide,
                'tourTitle' => $tourTitle,
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
        $attachments = [];

        // Attach the booking agreement PDF if it exists
        if ($this->booking->agreement_pdf_path && \Storage::exists($this->booking->agreement_pdf_path)) {
            $attachments[] = Attachment::fromStorage($this->booking->agreement_pdf_path)
                ->as('Booking_Agreement_' . $this->booking->booking_number . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
