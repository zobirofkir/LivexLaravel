<?php

namespace App\Services\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Constructors\AuthUserConstructor;
use Illuminate\Support\Facades\Auth;

class AuthUserService implements AuthUserConstructor
{
    /**
     * Update the authenticated user's information.
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = Auth::user();

        $validatedData = $request->validated();

        $user->update($validatedData);

        if ($user->profile) {
            $user->profile->update($validatedData);
        } else {
            $user->profile()->create($validatedData);
        }

        return UserResource::make($user->load('profile'));
    }

    /**
     * Get the authenticated user's information.
     */
    public function show(): UserResource
    {
        $auth = Auth::user();
        return UserResource::make($auth->load('profile'));
    }

}