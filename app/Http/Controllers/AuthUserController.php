<?php

namespace App\Http\Controllers;

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

}
