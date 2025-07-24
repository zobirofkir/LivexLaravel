<?php

namespace App\Models;

use App\Enums\LiveCategoryEnum;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $table = 'live_streams';

    protected $fillable = ['title', 'stream_key', 'thumbnail', 'is_live', 'user_id', 'live_category'];

    protected $casts = [
        'is_live' => 'boolean',
        'live_category' => LiveCategoryEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}