<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Inspection;
use Illuminate\Support\Facades\Storage;

class InspectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $inspection;
    public $permit;
    public $hasPhoto = false;

    /**
     * Create a new message instance.
     */
    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
        $this->permit = $inspection->permit;
        $this->hasPhoto = !empty($inspection->photo_path) && Storage::disk('public')->exists($inspection->photo_path);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->subject('New Inspection Report - ' . $this->permit->permit_number)
                    ->view('emails.inspection-notification')
                    ->with([
                        'inspection' => $this->inspection,
                        'permit' => $this->permit,
                        'hasPhoto' => $this->hasPhoto
                    ]);
        
        // Embed photo as inline attachment if exists
        if ($this->hasPhoto) {
            $photoPath = Storage::disk('public')->path($this->inspection->photo_path);
            $email->attachFromStorageDisk('public', $this->inspection->photo_path, 'inspection_photo.jpg', [
                'mime' => 'image/jpeg'
            ]);
        }
        
        return $email;
    }
}