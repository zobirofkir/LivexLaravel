<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    protected $fillable = [
        'user_id',
        'stream_key',
        'title',
        'started_at',
        'ended_at',
        'viewer_count',
        'likes_count'
    ];
}
