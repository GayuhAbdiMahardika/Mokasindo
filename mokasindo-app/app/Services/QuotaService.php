<?php

namespace App\Services;

use App\Exceptions\QuotaExceededException;
use App\Models\RoleQuota;
use App\Models\User;
use App\Models\UserQuotaOverride;
use App\Models\Vehicle;

class QuotaService
{
    /**
     * Ensure user is still allowed to publish/create a listing.
     */
    public function ensureCanCreateListing(User $user): void
    {
        if ($this->shouldBypass($user)) {
            return;
        }

        $remaining = $this->remainingListings($user);

        if ($remaining !== null && $remaining <= 0) {
            throw new QuotaExceededException('Batas maksimum iklan untuk akun Anda telah tercapai.');
        }
    }

    /**
     * Remaining slots the user can use before hitting the limit.
     */
    public function remainingListings(User $user): ?int
    {
        $limit = $this->listingLimit($user);

        if ($limit === null) {
            return null; // Unlimited
        }

        return max(0, $limit - $this->activeListingCount($user));
    }

    /**
     * Determine per-user listing limit (override > role quota > config default or unlimited).
     */
    public function listingLimit(User $user): ?int
    {
        $override = UserQuotaOverride::where('user_id', $user->id)->value('post_limit');
        if ($override !== null) {
            return (int) $override;
        }

        $roleLimit = RoleQuota::where('role', $user->role)->value('post_limit');
        if ($roleLimit !== null) {
            return (int) $roleLimit;
        }

        return config('quotas.default_limit');
    }

    /**
     * Count listings considered active for quota purposes.
     */
    public function activeListingCount(User $user): int
    {
        $statuses = config('quotas.counted_statuses', []);

        $query = Vehicle::where('user_id', $user->id);

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        return (int) $query->count();
    }

    protected function shouldBypass(User $user): bool
    {
        $bypassRoles = config('quotas.bypass_roles', []);
        return in_array($user->role, $bypassRoles, true);
    }
}
