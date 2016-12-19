<?php namespace ChaoticWave\SilentMovie\Console;

use ChaoticWave\SilentMovie\Console\Commands\Lookup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /** @inheritdoc */
    protected $commands = [
        Lookup::class,
    ];

    /** @inheritdoc */
    protected function schedule(Schedule $schedule)
    {
    }

    /** @inheritdoc */
    protected function commands()
    {
        /** @noinspection PhpIncludeInspection */
        require base_path('routes/console.php');
    }
}
