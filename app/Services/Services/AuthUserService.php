<?php

namespace App\Services\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\LiveStreamResource;
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
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
    
            $imagePath = Storage::disk('public')->putFile('profile_images', $validatedData['profile_image']);
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
    
        return UserResource::make($user->load(['profile', 'videos']));
    }
    
    /**
     * Get the authenticated user's information.
     */
    public function show(): UserResource
    {
        $auth = Auth::user();
        return UserResource::make($auth->load(['profile', 'videos', 'liveStreams']));
    }

    /**
     * Logout the authenticated user.
     * @return bool
     */
    public function logout(): bool
    {
        return Auth::user()->token()->revoke();
    }

    /**
     * List all reels created by the authenticated user.
     */
    public function listUserReels()
    {
        $user = Auth::user();
        return $user->videos; // Assuming 'videos' is the relationship name in the User model
    }

    /**
     * List all live streams created by the authenticated user.
     */
    public function listUserLiveStreams()
    {
        $liveStreams = Auth::user()->liveStreams;
        return LiveStreamResource::collection($liveStreams);
    }
}