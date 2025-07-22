<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
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
     * The attributes that should be appended to the model's array form.
     */
    public function generateToken()
    {
        return $this->createToken('accessToken')->accessToken;
    }

    /**
     * The attributes that should be appended to the model's array form.
     * 
     * @return HasOne
     */
    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * The attributes that should be appended to the model's array form.
     * 
     * @return HasMany
     */
    public function videos() : HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Create Raltion SHeep
     */
    public function live(): HasOne
    {
        return $this->hasOne(Live::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }
    
    /**
     * Get the offers created by the user.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function follow(User $user): void
    {
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
        }
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function totalLikes()
    {
        return $this->hasManyThrough(Like::class, Video::class);
    }

    public function totalEarnings()
    {
        return $this->earnings()->sum('amount');
    }
}
