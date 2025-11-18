<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Inspection;

class InspectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $inspection;
    public $permit;

    /**
     * Create a new message instance.
     */
    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
        $this->permit = $inspection->permit;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Inspection Report - ' . $this->permit->permit_number)
                    ->view('emails.inspection-notification')
                    ->with([
                        'inspection' => $this->inspection,
                        'permit' => $this->permit
                    ]);
    }
}