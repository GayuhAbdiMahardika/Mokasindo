<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckMembershipExpiry extends Command
{
    protected $signature = 'membership:check-expiry';
    protected $description = 'Downgrade expired memberships';

    public function handle()
    {
        User::where('role', 'member')
            ->whereNotNull('membership_expires_at')
            ->where('membership_expires_at', '<', now())
            ->update(['role' => 'anggota']);

        $this->info('Membership expired check completed.');
    }
}
