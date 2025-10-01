<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HraWorkAtHeight;
use App\Models\User;

class UpdateHraUserId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hra:update-user-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user_id for existing HRA records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating HRA records with user_id...');
        
        $adminUser = User::where('role', 'administrator')->first();
        
        if (!$adminUser) {
            $this->error('No administrator user found!');
            return 1;
        }
        
        $updated = HraWorkAtHeight::whereNull('user_id')->update(['user_id' => $adminUser->id]);
        
        $this->info("Updated {$updated} HRA records with user_id: {$adminUser->name}");
        
        return 0;
    }
}
