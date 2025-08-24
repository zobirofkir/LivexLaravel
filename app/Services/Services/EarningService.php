<?php

namespace App\Services\Services;

use App\Models\Earning;
use App\Models\User;
use App\Services\Constructors\EarningConstructor;

class EarningService implements EarningConstructor
{
    /**
     * Add a new earning for a user.
     *
     * @param User $user
     * @param float $amount
     * @param string $source
     * @param string|null $notes
     * @param float $platformFeePercentage
     * @return Earning
     */
    public function addEarning(User $user, float $amount, string $source, ?string $notes = null, float $platformFeePercentage = 20): Earning
    {
        return $user->earnings()->create([
            'amount' => $amount,
            'source' => $source,
            'notes' => $notes,
            'platform_fee_percentage' => $platformFeePercentage,
        ]);
    }

    /**
     * Get the total earnings for a user.
     *
     * @param User $user
     * @return float
     */
    public function getTotalEarnings(User $user): float
    {
        return $user->earnings()->sum('amount') ?? 0;
    }

    /**
     * Get the earning history for a user.
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEarningHistory(User $user, int $perPage = 15)
    {
        return $user->earnings()->latest()->paginate($perPage);
    }
}