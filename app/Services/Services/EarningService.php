<?php

namespace App\Services\Services;

use App\Models\Earning;
use App\Models\User;
use App\Services\Contracts\EarningConstructor;

class EarningService implements EarningConstructor
{
    /**
     * Add a new earning for a user.
     *
     * @param User $user
     * @param float $amount
     * @param string $source
     * @param string|null $notes
     * @return Earning
     */
    public function addEarning(User $user, float $amount, string $source, ?string $notes = null): Earning
    {
        return $user->earnings()->create([
            'amount' => $amount,
            'source' => $source,
            'notes' => $notes,
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
        return $user->earnings()->sum('amount');
    }

    public function getEarningHistory(User $user, int $perPage = 15)
    {
        return $user->earnings()->latest()->paginate($perPage);
    }
}