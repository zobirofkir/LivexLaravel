<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AnimalNameService;

class UserObserver
{
    protected AnimalNameService $animalNameService;

    public function __construct(AnimalNameService $animalNameService)
    {
        $this->animalNameService = $animalNameService;
    }

    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        if (empty($user->name)) {
            $user->name = $this->animalNameService->generateUniqueName();
        }
        
        if (empty($user->username)) {
            $user->username = $this->animalNameService->generateUniqueUsername();
        }
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        if (empty($user->name)) {
            $user->name = $this->animalNameService->generateUniqueName();
        }
        
        if (empty($user->username)) {
            $user->username = $this->animalNameService->generateUniqueUsername();
        }
    }
}