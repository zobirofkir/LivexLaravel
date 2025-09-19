<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use Illuminate\Console\Command;

class ClearLiveStreams extends Command
{
    protected $signature = 'livestreams:clear';
    protected $description = 'Clear all live streams from the database';

    public function handle()
    {
        LiveStream::truncate();
        $this->info('All live streams cleared.');
    }
}