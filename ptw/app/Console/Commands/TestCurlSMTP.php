<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCurlSMTP extends Command
{
    protected $signature = 'test:curl-smtp {recipient}';
    protected $description = 'Test SMTP with cURL';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        
        $this->info("Testing SMTP with cURL...");
        
        // Create a simple email content
        $email_content = "Subject: Test Email from cURL\r\n";
        $email_content .= "From: {$username}\r\n";
        $email_content .= "To: {$recipient}\r\n";
        $email_content .= "\r\n";
        $email_content .= "This is a test email sent via cURL SMTP.\r\n";
        
        // Write email content to temp file
        $temp_file = tempnam(sys_get_temp_dir(), 'smtp_test');
        file_put_contents($temp_file, $email_content);
        
        // Build cURL command
        $curl_cmd = sprintf(
            'curl -s -v --url "smtp://smtp.hostinger.com:587" --mail-from "%s" --mail-rcpt "%s" --user "%s:%s" --upload-file "%s" -k --insecure',
            $username,
            $recipient,
            $username,
            $password,
            $temp_file
        );
        
        $this->info("Running cURL command...");
        $this->info($curl_cmd);
        
        // Execute cURL
        $output = shell_exec($curl_cmd . ' 2>&1');
        
        $this->info("\n=== cURL Output ===");
        $this->info($output);
        
        // Clean up
        unlink($temp_file);
        
        return 0;
    }
}