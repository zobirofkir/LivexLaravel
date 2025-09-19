protected function schedule(Schedule $schedule)
{
    $schedule->command('offers:expire')->everyMinute();
    $schedule->job(new \App\Jobs\DeleteOldLiveStreams)->everyThirtySeconds();
}