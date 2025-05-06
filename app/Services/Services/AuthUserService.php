<?php

namespace App\Services\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Constructors\AuthUserConstructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthUserService implements AuthUserConstructor
{
    /**
     * Update the authenticated user's information.
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        if (isset($validatedData['profile_image']) && $validatedData['profile_image']) {
            $imagePath = $validatedData['profile_image']->store('profile_images', 'public');
            $validatedData['profile_image'] = $imagePath;
        } else {
            unset($validatedData['profile_image']);
        }

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

    /**
     * Logout the authenticated user.
     * @return bool
     */
    public function logout(): bool
    {
        return Auth::user()->token()->revoke();
    }

}