<?php

namespace App\Mail;

use App\Models\HraHotWork;
use App\Models\HraHotWorkInspection;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class HraInspectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $hra;
    public $inspection;
    public $permit;
    public $hasPhoto = false;

    public function __construct(HraHotWork $hra, HraHotWorkInspection $inspection)
    {
        $this->hra        = $hra;
        $this->inspection = $inspection;
        $this->permit     = $hra->permitToWork;
        $this->hasPhoto   = !empty($inspection->photo_path)
            && Storage::disk('public')->exists($inspection->photo_path);
    }

    public function build()
    {
        $email = $this
            ->subject(sprintf(
                'HRA Hot Work Inspection #%d - %s',
                $this->inspection->sequence,
                $this->hra->hra_permit_number ?? ($this->permit->permit_number ?? '')
            ))
            ->view('emails.hra-inspection-notification')
            ->with([
                'hra'        => $this->hra,
                'inspection' => $this->inspection,
                'permit'     => $this->permit,
                'hasPhoto'   => $this->hasPhoto,
                'required'   => $this->hra->requiredInspectionCount(),
            ]);

        if ($this->hasPhoto) {
            $email->attachFromStorageDisk('public', $this->inspection->photo_path, 'inspection_photo.jpg', [
                'mime' => 'image/jpeg',
            ]);
        }

        return $email;
    }
}
