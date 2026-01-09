<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PermitToWork;

class HraApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $hra;
    public $permit;
    public $hraType;
    public $approvalUrl;

    /**
     * Create a new message instance.
     * Accepts any HRA model type (HraHotWork, HraLotoIsolation, etc.)
     */
    public function __construct($hra, PermitToWork $permit, string $hraType = 'Hot Work', string $approvalUrl = '')
    {
        $this->hra = $hra;
        $this->permit = $permit;
        $this->hraType = $hraType;
        $this->approvalUrl = $approvalUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'HRA ' . $this->hraType . ' Approval Request - ' . $this->hra->hra_permit_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hra-approval-request',
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
