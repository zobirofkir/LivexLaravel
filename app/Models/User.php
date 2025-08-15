<?php

namespace App\Models;

use App\Traits\HasAnimalNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->name)) {
                $user->name = self::generateUniqueAnimalName();
            }
            if (empty($user->username)) {
                $user->username = self::generateUniqueAnimalUsername();
            }
        });

        static::updating(function (User $user) {
            if (empty($user->name)) {
                $user->name = self::generateUniqueAnimalName();
            }
            if (empty($user->username)) {
                $user->username = self::generateUniqueAnimalUsername();
            }
        });
    }

    /**
     * Generate a unique animal name for display
     */
    protected static function generateUniqueAnimalName(): string
    {
        $animals = [
            'Lion', 'Tiger', 'Elephant', 'Giraffe', 'Zebra', 'Panda', 'Koala', 
            'Dolphin', 'Whale', 'Eagle', 'Falcon', 'Owl', 'Penguin', 'Flamingo',
            'Butterfly', 'Dragonfly', 'Octopus', 'Seahorse', 'Turtle', 'Rabbit',
            'Fox', 'Wolf', 'Bear', 'Deer', 'Moose', 'Kangaroo', 'Cheetah', 
            'Leopard', 'Jaguar', 'Lynx', 'Otter', 'Seal', 'Walrus', 'Hippo',
            'Rhino', 'Crocodile', 'Iguana', 'Chameleon', 'Gecko', 'Parrot',
            'Shark', 'Stingray', 'Jellyfish', 'Starfish', 'Lobster', 'Crab',
            'Peacock', 'Swan', 'Hummingbird', 'Woodpecker', 'Toucan', 'Pelican'
        ];

        do {
            $randomAnimal = $animals[array_rand($animals)];
            $uniqueNumber = rand(1000, 9999);
            $name = $randomAnimal . ' ' . $uniqueNumber;
        } while (self::where('name', $name)->exists());

        return $name;
    }

    /**
     * Generate a unique animal username (lowercase, no spaces)
     */
    protected static function generateUniqueAnimalUsername(): string
    {
        $animals = [
            'lion', 'tiger', 'elephant', 'giraffe', 'zebra', 'panda', 'koala', 
            'dolphin', 'whale', 'eagle', 'falcon', 'owl', 'penguin', 'flamingo',
            'butterfly', 'dragonfly', 'octopus', 'seahorse', 'turtle', 'rabbit',
            'fox', 'wolf', 'bear', 'deer', 'moose', 'kangaroo', 'cheetah', 
            'leopard', 'jaguar', 'lynx', 'otter', 'seal', 'walrus', 'hippo',
            'rhino', 'crocodile', 'iguana', 'chameleon', 'gecko', 'parrot',
            'shark', 'stingray', 'jellyfish', 'starfish', 'lobster', 'crab',
            'peacock', 'swan', 'hummingbird', 'woodpecker', 'toucan', 'pelican'
        ];

        do {
            $randomAnimal = $animals[array_rand($animals)];
            $uniqueNumber = rand(1000, 9999);
            $username = $randomAnimal . '_' . $uniqueNumber;
        } while (self::where('username', $username)->exists());

        return $username;
    }

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
    public function videos()
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

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')->withTimestamps();
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Add this accessor to the User model
     */
    public function getNameAttribute($value)
    {
        if (!$value) {
            // Generate a random animal name if name is empty
            $animals = [
                'Lion', 'Tiger', 'Elephant', 'Giraffe', 'Zebra', 'Panda', 'Koala', 
                'Dolphin', 'Whale', 'Eagle', 'Falcon', 'Owl', 'Penguin', 'Flamingo',
                'Butterfly', 'Dragonfly', 'Octopus', 'Seahorse', 'Turtle', 'Rabbit',
                'Fox', 'Wolf', 'Bear', 'Deer', 'Moose', 'Kangaroo', 'Cheetah', 
                'Leopard', 'Jaguar', 'Lynx', 'Otter', 'Seal', 'Walrus', 'Hippo',
                'Rhino', 'Crocodile', 'Iguana', 'Chameleon', 'Gecko', 'Parrot'
            ];
            
            $randomAnimal = $animals[array_rand($animals)];
            $uniqueNumber = $this->id ?? rand(1000, 9999);
            
            return $randomAnimal . ' ' . $uniqueNumber;
        }
        
        return $value;
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

}
