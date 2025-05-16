<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Live extends Model
{
    /**
     * Filables
     */
    protected $fillable = [
        'user_id',
        'stream_key',
        'title',
        'started_at',
        'ended_at',
        'viewer_count',
        'likes_count'
    ];

    /**
     * User Relation Sheep
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
