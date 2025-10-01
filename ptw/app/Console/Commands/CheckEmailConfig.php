<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:email-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check email configuration and SMTP connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== EMAIL CONFIGURATION CHECK ===');
        
        // Display current email configuration
        $this->info('Current Email Configuration:');
        $this->table(['Setting', 'Value'], [
            ['MAIL_MAILER', config('mail.default')],
            ['MAIL_HOST', config('mail.mailers.smtp.host')],
            ['MAIL_PORT', config('mail.mailers.smtp.port')],
            ['MAIL_USERNAME', config('mail.mailers.smtp.username')],
            ['MAIL_PASSWORD', config('mail.mailers.smtp.password') ? '***hidden***' : 'NOT SET'],
            ['MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')],
            ['MAIL_FROM_ADDRESS', config('mail.from.address')],
            ['MAIL_FROM_NAME', config('mail.from.name')],
        ]);

        // Test SMTP connection
        $this->info('');
        $this->info('Testing SMTP Connection...');
        
        try {
            // Create a simple test email
            Mail::raw('This is a test email to verify SMTP configuration.', function ($message) {
                $message->to('test@example.com')
                        ->subject('SMTP Configuration Test - ' . now());
            });
            
            $this->info('✅ SMTP connection test passed! (Email queued/sent successfully)');
            
        } catch (\Swift_TransportException $e) {
            $this->error('❌ SMTP Connection Failed:');
            $this->error('Error: ' . $e->getMessage());
            
            // Common SMTP errors and solutions
            if (str_contains($e->getMessage(), 'Connection refused')) {
                $this->warn('💡 Tip: Check if SMTP host and port are correct');
            } elseif (str_contains($e->getMessage(), 'Authentication failed')) {
                $this->warn('💡 Tip: Check your email username and password');
            } elseif (str_contains($e->getMessage(), 'Connection timed out')) {
                $this->warn('💡 Tip: Check if port 587 is blocked by firewall');
            }
            
            return 1;
            
        } catch (\Exception $e) {
            $this->error('❌ General Email Error:');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        // Check if .env file has email settings
        $this->info('');
        $this->info('Environment File Check:');
        
        if (!config('mail.mailers.smtp.username')) {
            $this->warn('⚠️  MAIL_USERNAME not set in .env file');
        }
        
        if (!config('mail.mailers.smtp.password')) {
            $this->warn('⚠️  MAIL_PASSWORD not set in .env file');
        }
        
        if (!config('mail.from.address')) {
            $this->warn('⚠️  MAIL_FROM_ADDRESS not set in .env file');
        }

        $this->info('');
        $this->info('=== HOSTINGER SMTP SETTINGS ===');
        $this->info('Expected Configuration for Hostinger:');
        $this->table(['Setting', 'Expected Value'], [
            ['MAIL_HOST', 'smtp.hostinger.com'],
            ['MAIL_PORT', '587 (TLS) or 465 (SSL)'],
            ['MAIL_ENCRYPTION', 'tls (for port 587) or ssl (for port 465)'],
            ['MAIL_USERNAME', 'your-full-email@yourdomain.com'],
            ['MAIL_PASSWORD', 'your-email-account-password'],
        ]);

        $this->info('');
        $this->info('Next Steps to check in Hostinger:');
        $this->info('1. Login to hpanel.hostinger.com');
        $this->info('2. Go to Email Accounts');
        $this->info('3. Make sure email account exists and password is correct');
        $this->info('4. Check Email Usage/Statistics if available');
        $this->info('5. Look for Email Logs or Delivery Reports');

        return 0;
    }
}
