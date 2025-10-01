<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PermitApprovalRequest;

class TestEmailApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-approval {permitId} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email approval notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permitId = $this->argument('permitId');
        $email = $this->argument('email');

        // Find permit
        $permit = PermitToWork::find($permitId);
        
        if (!$permit) {
            $this->error("Permit with ID {$permitId} not found!");
            return 1;
        }

        try {
            // Send test email
            Mail::to($email)->send(new PermitApprovalRequest($permit));
            
            $this->info("✅ Test email sent successfully!");
            $this->info("Permit: {$permit->permit_number}");
            $this->info("To: {$email}");
            $this->info("Subject: Permit Approval Request - {$permit->permit_number}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            return 1;
        }
    }
}
