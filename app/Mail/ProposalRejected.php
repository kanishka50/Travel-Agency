<?php

namespace App\Mail;

use App\Models\PlanProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalRejected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PlanProposal $proposal;

    /**
     * Create a new message instance.
     */
    public function __construct(PlanProposal $proposal)
    {
        $this->proposal = $proposal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your Tour Proposal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.proposal-rejected',
            with: [
                'proposal' => $this->proposal,
                'plan' => $this->proposal->guidePlan,
                'guide' => $this->proposal->guidePlan->guide,
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
