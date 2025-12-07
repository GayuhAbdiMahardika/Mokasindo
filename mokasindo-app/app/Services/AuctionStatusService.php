<?php

namespace App\Services;

use App\Models\Auction;
use Carbon\Carbon;

class AuctionStatusService
{
    /**
     * Sync auction status based on start_time/end_time without cron.
     */
    public function sync(): void
    {
        $now = Carbon::now();

        // scheduled -> active
        Auction::where('status', 'scheduled')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => 'active']);

        // active -> ended
        Auction::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'ended']);

        // scheduled but already expired -> cancelled
        Auction::where('status', 'scheduled')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'cancelled']);
    }
}
