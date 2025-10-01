<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class TestEmailDebug extends Command
{
    protected $signature = 'test:email-debug {recipient}';
    protected $description = 'Debug SMTP connection directly';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        try {
            $mail = new PHPMailer(true);
            
            // Enable verbose debug output
            $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
            $mail->Debugoutput = function($str, $level) {
                $this->info("DEBUG ($level): $str");
            };

            // Server settings
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            
            $port = config('mail.mailers.smtp.port');
            $encryption = config('mail.mailers.smtp.encryption');
            
            if ($port == 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
            }
            $mail->Port = $port;
            
            // Recipients
            $mail->setFrom('no-reply@ptw.aplikasibebas.com', 'PTW Portal');
            $mail->addAddress($recipient);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'SMTP Debug Test';
            $mail->Body = 'This is a debug test email.';

            $mail->send();
            $this->info('✅ Email sent successfully!');
            
        } catch (Exception $e) {
            $this->error("❌ Email failed: {$mail->ErrorInfo}");
        }
    }
}