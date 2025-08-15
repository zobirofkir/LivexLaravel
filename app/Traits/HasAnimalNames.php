<?php

namespace App\Traits;

trait HasAnimalNames
{
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
        } while (static::where('name', $name)->exists());

        return $name;
    }

    /**
     * Boot the trait
     */
    protected static function bootHasAnimalNames(): void
    {
        static::creating(function ($model) {
            if (empty($model->name)) {
                $model->name = static::generateUniqueAnimalName();
            }
            if (empty($model->username)) {
                $model->username = static::generateUniqueAnimalUsername();
            }
        });

        static::updating(function ($model) {
            if (empty($model->name)) {
                $model->name = static::generateUniqueAnimalName();
            }
            if (empty($model->username)) {
                $model->username = static::generateUniqueAnimalUsername();
            }
        });
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
        } while (static::where('username', $username)->exists());

        return $username;
    }
}