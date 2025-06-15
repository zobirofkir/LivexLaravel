<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = ['title', 'stream_key', 'is_live', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
