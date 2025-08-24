<?php

namespace App\Services\Constructors;

interface EarningConstructor
{
    public function addEarning($user, float $amount, string $source, ?string $notes = null, float $platformFeePercentage = 20);
}