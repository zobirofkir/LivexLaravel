<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Facades\LiveFacade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class LiveController extends Controller
{
    /**
     * Start User Live
     */
    public function goLive(): bool
    {
        return LiveFacade::goLive();
    }

    /**
     * Stop Live
     */
    public function stopLive(): bool
    {
        return LiveFacade::stopLive();
    }

    /**
     * Get Live Users
     */
    public function getLiveUsers(): AnonymousResourceCollection
    {
        return LiveFacade::getLiveUsers();
    }
}
