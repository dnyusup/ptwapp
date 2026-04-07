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
    public $result; // true for approved, false for rejected
    public $comment;
    public $type; // 'permit' or 'extension'
    public $rejectedBy; // 'ehs' or 'location_owner'

    public function __construct(PermitToWork $permit, $result, $type = 'permit', $comment = null, $rejectedBy = 'ehs')
    {
        $this->permit = $permit;
        $this->result = $result;
        $this->type = $type;
        $this->comment = $comment;
        $this->rejectedBy = $rejectedBy;
    }

    public function build()
    {
        if ($this->type === 'extension') {
            $subject = $this->result 
                ? $this->permit->work_title . ' - Permit Extension Approved - ' . $this->permit->permit_number
                : $this->permit->work_title . ' - Permit Extension Rejected - ' . $this->permit->permit_number;
        } else {
            $subject = $this->result 
                ? $this->permit->work_title . ' - Permit Approved - ' . $this->permit->permit_number
                : $this->permit->work_title . ' - Permit Rejected - ' . $this->permit->permit_number;
        }
        
        return $this->subject($subject)
            ->view('emails.permit-approval-result');
    }
}
