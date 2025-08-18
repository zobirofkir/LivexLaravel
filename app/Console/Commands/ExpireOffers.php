<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Illuminate\Console\Command;

class ExpireOffers extends Command
{
    protected $signature = 'offers:expire';
    protected $description = 'Automatically deactivate expired offers';

    public function handle()
    {
        $expiredCount = Offer::where('is_active', true)
            ->where('valid_until', '<', now())
            ->update(['is_active' => false]);

        $this->info("Deactivated {$expiredCount} expired offers.");
        
        return 0;
    }
}