<?php

namespace App\Services\Constructors;

use App\Models\Earning;
use App\Models\User;

interface EarningConstructor
{
    public function addEarning(User $user, float $amount, string $source, ?string $notes = null, float $platformFeePercentage = 20): Earning;
}