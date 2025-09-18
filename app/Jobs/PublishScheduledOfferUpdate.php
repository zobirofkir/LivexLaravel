<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Events\OfferChangedEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishScheduledOfferUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Offer $offer
    ) {}

    public function handle(): void
    {
        if (!$this->offer->has_pending_updates || !$this->offer->pending_updates) {
            return;
        }

        $pendingUpdates = $this->offer->pending_updates;
        
        // Apply the pending updates
        $this->offer->update(array_merge($pendingUpdates, [
            'pending_updates' => null,
            'scheduled_publish_at' => null,
            'has_pending_updates' => false,
            'force_refresh' => true
        ]));

        event(new OfferChangedEvent($this->offer));
    }
}