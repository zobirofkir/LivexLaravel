<?php

namespace App\Services\Constructors;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

interface AuthUserConstructor
{
    /**
     * Update the authenticated user's information.
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function update(UpdateUserRequest $request): UserResource;

    /**
     * Get the authenticated user's information.
     * @return UserResource
     */
    public function show(): UserResource;
}