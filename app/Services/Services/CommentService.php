<?php

namespace App\Services\Services;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

class CommentService
{
    public function addComment(User $user, Video $video, string $content): Comment
    {
        return Comment::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'content' => $content,
        ]);
    }

    public function getComments(Video $video)
    {
        return $video->comments()->latest()->get();
    }
}
