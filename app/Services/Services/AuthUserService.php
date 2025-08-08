<?php

namespace App\Services\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\LiveStreamResource;
use App\Services\Constructors\AuthUserConstructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuthUserService implements AuthUserConstructor
{
    /**
     * Update the authenticated user's information.
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = Auth::user();
        $validatedData = $request->validated();
    
        try {
            DB::beginTransaction();
            
            // Handle profile image upload
            if (isset($validatedData['profile_image']) && $validatedData['profile_image']) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
        
                $imagePath = Storage::disk('public')->putFile('profile_images', $validatedData['profile_image']);
                $validatedData['profile_image'] = $imagePath;
            } else {
                unset($validatedData['profile_image']);
            }
        
            // Separate user fields from profile fields
            $userFields = array_intersect_key($validatedData, array_flip(['name', 'email', 'phone_number', 'profile_image']));
            $profileFields = array_intersect_key($validatedData, array_flip(['first_name', 'last_name', 'bio', 'phone', 'address']));
        
            // Update user fields if any exist
            if (!empty($userFields)) {
                $user->update($userFields);
            }
        
            // Update or create profile if profile fields exist
            if (!empty($profileFields)) {
                if ($user->profile) {
                    $user->profile->update($profileFields);
                } else {
                    $profileFields['user_id'] = $user->id;
                    $user->profile()->create($profileFields);
                }
            }
            
            DB::commit();
            
            return UserResource::make($user->fresh(['profile', 'videos']));
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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