<?php

namespace App\Services\Services;

use App\Models\Earning;
use App\Models\User;
use App\Services\Constructors\EarningConstructor;

class EarningService implements EarningConstructor
{
    public function addEarning(User $user, float $amount, string $source): Earning
    {
        return $user->earnings()->create([
            'amount' => $amount,
            'source' => $source,
        ]);
    }

    public function getTotalEarnings(User $user): float
    {
        return $user->earnings()->sum('amount');
    }

    public function getEarningHistory(User $user, int $perPage = 15)
    {
        return $user->earnings()->latest()->paginate($perPage);
    }
}