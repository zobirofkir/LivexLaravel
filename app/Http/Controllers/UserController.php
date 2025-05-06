<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Update the authenticated user's information.
     */
    public function update(UpdateUserRequest $request)
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
    public function show()
    {
        $auth = Auth::user();
        return UserResource::make($auth->load('profile'));
    }
}
