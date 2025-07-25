<?php

namespace App\Services\Constructors;

use App\Models\User;
use App\Models\Video;

interface LikeConstructor
{
    public function canUnlike(User $user, Video $video): bool;
    public function canCheckIsLiked(User $user, Video $video): bool;
}