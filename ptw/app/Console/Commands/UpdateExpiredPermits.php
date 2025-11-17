<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PermitToWork;

class UpdateExpiredPermits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permits:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired permits status from active to expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired permits...');
        
        $expiredCount = PermitToWork::updateExpiredPermits();
        
        if ($expiredCount > 0) {
            $this->info("Updated {$expiredCount} permit(s) to expired status.");
        } else {
            $this->info('No expired permits found.');
        }
        
        return 0;
    }
}
