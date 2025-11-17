<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PermitToWork;

class PermitExtensionRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $permit;
    public $originalEndDate;
    public $newEndDate;

    /**
     * Create a new message instance.
     */
    public function __construct(PermitToWork $permit, $originalEndDate, $newEndDate)
    {
        $this->permit = $permit;
        $this->originalEndDate = $originalEndDate;
        $this->newEndDate = $newEndDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permit Extension Request - ' . $this->permit->permit_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.permit-extension-request',
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
