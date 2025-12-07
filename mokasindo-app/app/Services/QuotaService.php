<?php

namespace App\Services;

use App\Exceptions\QuotaExceededException;
use App\Models\User;

class QuotaService
{
    /**
     * Enforce current business rule: anggota max 2 listings per week; member/admin/owner unlimited.
     */
    public function ensureCanCreateListing(User $user): void
    {
        if ($this->isUnlimited($user)) {
            return;
        }

        if (!$user->isAnggota()) {
            return;
        }

        $this->refreshWeeklyCounter($user);

        if ($user->weekly_post_count >= 2) {
            throw new QuotaExceededException('Akun Anggota hanya dapat membuat 2 listing per minggu. Upgrade ke Member untuk posting tanpa batas.');
        }
    }

    /**
     * Record a successful listing creation for anggota users.
     */
    public function recordListingCreation(User $user): void
    {
        if (!$user->isAnggota()) {
            return;
        }

        $this->refreshWeeklyCounter($user);

        $user->increment('weekly_post_count');

        if ($user->last_post_reset === null) {
            $user->forceFill(['last_post_reset' => now()])->save();
        }
    }

    private function refreshWeeklyCounter(User $user): void
    {
        if ($user->last_post_reset === null || $user->last_post_reset->diffInDays(now()) >= 7) {
            $user->forceFill([
                'weekly_post_count' => 0,
                'last_post_reset' => now(),
            ])->save();
        }
    }

    private function isUnlimited(User $user): bool
    {
        return $user->isMember() || $user->isAdmin() || $user->isOwner();
    }
}
