<?php

namespace App\Services\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Constructors\AuthUserConstructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class AuthUserService implements AuthUserConstructor
{
    /**
     * Update the authenticated user's information.
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $user = Auth::user();
        $validatedData = $request->validated();
    
        Log::info('Validated Data:', $validatedData); // تسجيل البيانات القادمة
    
        if (
            isset($validatedData['profile_image']) &&
            $validatedData['profile_image'] instanceof UploadedFile
        ) {
            $imagePath = $validatedData['profile_image']->store('profile_images', 'public');
            $validatedData['profile_image'] = $imagePath;
    
            Log::info('Profile image stored at: ' . $imagePath);
    
            $user->update(['profile_image' => $imagePath]);
        } else {
            Log::warning('No valid profile image uploaded');
            unset($validatedData['profile_image']);
        }
    
        Log::info('Updating profile with data:', $validatedData);
    
        if ($user->profile) {
            $user->profile->update($validatedData);
            Log::info('Updated existing profile');
        } else {
            $user->profile()->create($validatedData);
            Log::info('Created new profile');
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