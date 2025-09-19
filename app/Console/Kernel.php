protected function schedule(Schedule $schedule)
{
    $schedule->command('offers:expire')->everyMinute();
    $schedule->command('livestreams:clear')->everyMinute();
}