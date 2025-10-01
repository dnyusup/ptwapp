<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSMTPAuth extends Command
{
    protected $signature = 'test:smtp-auth';
    protected $description = 'Test SMTP authentication directly';

    public function handle()
    {
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        
        $this->info("Testing SMTP Authentication...");
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));
        $this->info("Username: " . $username);
        $this->info("Password: " . str_repeat('*', strlen($password)));
        
        // Base64 encode credentials for AUTH PLAIN
        $auth_plain = base64_encode("\0" . $username . "\0" . $password);
        $username_b64 = base64_encode($username);
        $password_b64 = base64_encode($password);
        
        $this->info("\n=== Encoded Credentials ===");
        $this->info("Username (base64): " . $username_b64);
        $this->info("Password (base64): " . $password_b64);
        $this->info("AUTH PLAIN (base64): " . $auth_plain);
        
        $this->info("\n=== Manual Test Commands ===");
        $this->info("You can test manually with telnet:");
        $this->info("telnet smtp.hostinger.com 587");
        $this->info("EHLO localhost");
        $this->info("STARTTLS");
        $this->info("EHLO localhost");
        $this->info("AUTH LOGIN");
        $this->info($username_b64);
        $this->info($password_b64);
        
        return 0;
    }
}