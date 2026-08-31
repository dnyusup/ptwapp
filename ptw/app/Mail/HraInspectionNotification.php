<?php

namespace App\Mail;

use App\Models\HraHotWork;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class HraInspectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $hra;
    public $permit;
    public $hasPhoto = false;

    public function __construct(HraHotWork $hra)
    {
        $this->hra = $hra;
        $this->permit = $hra->permitToWork;
        $this->hasPhoto = !empty($hra->inspection_photo_path)
            && Storage::disk('public')->exists($hra->inspection_photo_path);
    }

    public function build()
    {
        $email = $this
            ->subject('HRA Hot Work Inspection - ' . ($this->hra->hra_permit_number ?? $this->permit->permit_number))
            ->view('emails.hra-inspection-notification')
            ->with([
                'hra'      => $this->hra,
                'permit'   => $this->permit,
                'hasPhoto' => $this->hasPhoto,
            ]);

        if ($this->hasPhoto) {
            $email->attachFromStorageDisk('public', $this->hra->inspection_photo_path, 'inspection_photo.jpg', [
                'mime' => 'image/jpeg',
            ]);
        }

        return $email;
    }
}
