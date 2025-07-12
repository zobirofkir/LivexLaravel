<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $table = 'live_streams';

    protected $fillable = ['title', 'stream_key', 'thumbnail', 'is_live', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
