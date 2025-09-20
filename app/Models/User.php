<?php

namespace App\Models;

use App\Services\AnimalNameService;
use App\Traits\HasAnimalNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasAnimalNames;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone_number',
        'profile_image',
        'is_live'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Generate access token for API authentication
     */
    public function generateToken()
    {
        return $this->createToken('accessToken')->accessToken;
    }

    /**
     * Get the user's profile
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the user's videos
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the user's live stream
     */
    public function live(): HasOne
    {
        return $this->hasOne(Live::class);
    }

    /**
     * Get the user's live streams
     */
    public function liveStreams(): HasMany
    {
        return $this->hasMany(LiveStream::class);
    }
    
    /**
     * Get the offers created by the user
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get the user's followers
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * Get users that this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow another user
     */
    public function follow(User $user): void
    {
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
        }
    }

    /**
     * Get the user's likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get total likes through videos
     */
    public function totalLikes()
    {
        return $this->hasManyThrough(Like::class, Video::class);
    }

    /**
     * Get the user's comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the messages sent by the user
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Name accessor - generates fallback name if empty
     */
    public function getNameAttribute($value)
    {
        if (!$value) {
            $animalNameService = app(AnimalNameService::class);
            return $animalNameService->generateFallbackName($this->id);
        }
        
        return $value;
    }

    /**
     * Get the count of unread received messages
     */
    public function unreadReceivedMessagesCount()
    {
        return $this->receivedMessages()->where('unread', true)->count();
    }

    /**
     * Get the count of all received messages
     */
    public function receivedMessagesCount()
    {
        return $this->receivedMessages()->count();
    }

    /**
     * Get unread messages count grouped by receiver email
     */
    public function unreadMessagesByReceiverEmail()
    {
        return Message::where('sender_id', $this->id)
            ->where('unread', true)
            ->with('receiver:id,email')
            ->get()
            ->groupBy('receiver.email')
            ->map(function ($messages) {
                return [
                    'receiver_email' => $messages->first()->receiver->email,
                    'unread_count' => $messages->count()
                ];
            })
            ->values();
    }

    /**
     * Get unread messages count for a specific receiver user by ID
     */
    public static function getUnreadMessagesCountByReceiverId($receiverId)
    {
        return Message::where('receiver_id', $receiverId)
            ->where('unread', true)
            ->count();
    }

    /**
     * Get unread messages count for this user as receiver
     */
    public function getUnreadMessagesCountAsReceiver()
    {
        return $this->receivedMessages()->where('unread', true)->count();
    }
}