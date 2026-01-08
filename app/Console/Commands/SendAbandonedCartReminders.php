<?php

namespace App\Console\Commands;

use App\Services\AbandonedCartService;
use Illuminate\Console\Command;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'cart:send-reminders {--hours=1 : Hours since cart was abandoned}';
    protected $description = 'Send reminder emails for abandoned carts';

    public function __construct(protected AbandonedCartService $abandonedCartService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        
        $this->info("Sending abandoned cart reminders for carts abandoned {$hours}+ hours ago...");
        
        $result = $this->abandonedCartService->sendScheduledReminders($hours);
        
        $this->info("Sent {$result['sent']} reminders.");
        
        if ($result['failed'] > 0) {
            $this->warn("Failed to send {$result['failed']} reminders.");
        }
        
        return Command::SUCCESS;
    }
}
