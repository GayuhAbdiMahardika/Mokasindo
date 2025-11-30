<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        \App\Console\Commands\CheckMembershipExpiry::class,
    ];
    
    protected function schedule(Schedule $schedule): void
    {
        // Scheduler Membership Downgrade
        $schedule->call(function () {
            \App\Models\User::where('role', 'member')
                ->whereNotNull('membership_expires_at')
                ->where('membership_expires_at', '<', now())
                ->update(['role' => 'anggota']);
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
