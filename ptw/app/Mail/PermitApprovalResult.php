<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PermitToWork;

class PermitApprovalResult extends Mailable
{
    use Queueable, SerializesModels;

    public $permit;
    public $result; // 'approved' or 'rejected'
    public $comment;

    public function __construct(PermitToWork $permit, $result, $comment = null)
    {
        $this->permit = $permit;
        $this->result = $result;
        $this->comment = $comment;
    }

    public function build()
    {
        $subject = $this->result === 'approved'
            ? 'Permit Approved - ' . $this->permit->permit_number
            : 'Permit Rejected - ' . $this->permit->permit_number;
        return $this->subject($subject)
            ->view('emails.permit-approval-result');
    }
}
