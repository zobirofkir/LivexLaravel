<?php

namespace App\Http\Controllers\api_v1\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Facades\AuthUserFacade;

class AuthUserController extends Controller
{
    /**
     * Update the authenticated user's information.
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        return AuthUserFacade::update($request);
    }

    /**
     * Get the authenticated user's information.
     * @return UserResource
     */
    public function show(): UserResource
    {
        return AuthUserFacade::show();
    }


    /**
     * Logout the authenticated user.
     * @return bool
     */
    public function logout(): bool
    {
        return AuthUserFacade::logout();
    }

    /**
     * List live streams created by the authenticated user.
     */
    public function liveStreams()
    {
        return AuthUserFacade::listUserLiveStreams();
    }

}
