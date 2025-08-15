<?php

namespace App\Services;

use App\Models\User;

class AnimalNameService
{
    /**
     * List of animals for name generation
     */
    protected array $animals = [
        'lion', 'tiger', 'elephant', 'giraffe', 'zebra', 'panda', 'koala', 
        'dolphin', 'whale', 'eagle', 'falcon', 'owl', 'penguin', 'flamingo',
        'butterfly', 'dragonfly', 'octopus', 'seahorse', 'turtle', 'rabbit',
        'fox', 'wolf', 'bear', 'deer', 'moose', 'kangaroo', 'cheetah', 
        'leopard', 'jaguar', 'lynx', 'otter', 'seal', 'walrus', 'hippo',
        'rhino', 'crocodile', 'iguana', 'chameleon', 'gecko', 'parrot',
        'shark', 'stingray', 'jellyfish', 'starfish', 'lobster', 'crab',
        'peacock', 'swan', 'hummingbird', 'woodpecker', 'toucan', 'pelican'
    ];

    /**
     * Generate a unique animal name for display (Title Case with space)
     */
    public function generateUniqueName(): string
    {
        do {
            $randomAnimal = ucfirst($this->animals[array_rand($this->animals)]);
            $uniqueNumber = rand(1000, 9999);
            $name = $randomAnimal . ' ' . $uniqueNumber;
        } while (User::where('name', $name)->exists());

        return $name;
    }

    /**
     * Generate a unique animal username (lowercase with underscore)
     */
    public function generateUniqueUsername(): string
    {
        do {
            $randomAnimal = $this->animals[array_rand($this->animals)];
            $uniqueNumber = rand(1000, 9999);
            $username = $randomAnimal . '_' . $uniqueNumber;
        } while (User::where('username', $username)->exists());

        return $username;
    }

    /**
     * Generate a fallback name for accessor (when name is null)
     */
    public function generateFallbackName(?int $userId = null): string
    {
        $randomAnimal = ucfirst($this->animals[array_rand($this->animals)]);
        $uniqueNumber = $userId ?? rand(1000, 9999);
        
        return $randomAnimal . ' ' . $uniqueNumber;
    }
}