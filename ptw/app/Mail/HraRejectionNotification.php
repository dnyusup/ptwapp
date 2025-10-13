<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HraHotWork;
use App\Models\PermitToWork;

class HraRejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $hraHotWork;
    public $permit;
    public $rejectionReason;
    public $rejectedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(HraHotWork $hraHotWork, PermitToWork $permit, $rejectionReason, $rejectedBy)
    {
        $this->hraHotWork = $hraHotWork;
        $this->permit = $permit;
        $this->rejectionReason = $rejectionReason;
        $this->rejectedBy = $rejectedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HRA Hot Work Rejected - ' . $this->hraHotWork->hra_permit_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hra-rejection-notification',
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
