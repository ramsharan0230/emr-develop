<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\NearExpiryThirtyDays::class,
        Commands\NearExpirySixtyDays::class,
        Commands\NearExpiryNintyDays::class,
        Commands\NearExpiryOneEightyDays::class,
        Commands\ExpiredItems::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('quote:expirythirtydays')->cron('0 11 * */1 *');
        $schedule->command('quote:expirysixtydays')->cron('0 11 * */2 *');
        $schedule->command('quote:expirynintydays')->cron('0 11 * */3 *');
        $schedule->command('quote:expiryoneeightydays')->cron('0 11 * */3 *');
        $schedule->command('quote:expireitem')->cron('0 11 * */1 *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
