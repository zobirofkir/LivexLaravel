<?php

namespace App\Services\Constructors;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface LiveConstructor
{
    /**
     * Start User Live
     */
    public function goLive(): bool;

    /**
     * Stop Live
     */
    public function stopLive(): bool;

    /**
     * Get Live Users
     */
    public function getLiveUsers(): AnonymousResourceCollection;
}