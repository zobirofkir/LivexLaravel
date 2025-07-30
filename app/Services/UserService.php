<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getAllUsersOrderedByDesc()
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    public function getAllUsersExceptCurrentAndAdmin()
    {
        $currentUserId = Auth::id();
        return User::where('id', '!=', $currentUserId)
                    ->where('name', '!=', 'livex')
                    ->where('email', '!=', 'admin@livex.com')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}