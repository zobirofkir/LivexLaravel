<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getAllUsersOrderedByDesc()
    {
        return User::orderBy('created_at', 'desc')->get();
    }
}