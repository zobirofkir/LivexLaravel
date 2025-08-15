<?php

namespace App\Traits;

trait HasAnimalNames
{
    /**
     * Generate a unique animal name
     */
    public static function generateUniqueAnimalName(): string
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
        });

        static::updating(function ($model) {
            if (empty($model->name)) {
                $model->name = static::generateUniqueAnimalName();
            }
        });
    }
}