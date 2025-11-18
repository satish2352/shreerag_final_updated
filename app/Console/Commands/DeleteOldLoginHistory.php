<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class DeleteOldLoginHistory extends Command
{
    protected $signature = 'login-history:cleanup';
    protected $description = 'Delete login history records older than 15 days';

    public function handle()
    {
        $dateLimit = Carbon::now()->subDays(15);

        // Delete records older than 15 days
        $count = LoginHistory::where('created_at', '<', $dateLimit)->delete();

        Log::info("Deleted {$count} old login history records");
        $this->info("Deleted {$count} old login history records");
    }
}
