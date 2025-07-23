<?php

namespace App\Http\Controllers\api_v1\actions;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Video;
use App\Services\Facades\CommentFacade;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request, Video $video)
    {
        $user = $request->user();
        $content = $request->input('content');

        $comment = CommentFacade::addComment($user, $video, $content);

        return new CommentResource($comment);
    }

    public function listComments(Video $video)
    {
        $comments = CommentFacade::getComments($video);

        return CommentResource::collection($comments);
    }
}