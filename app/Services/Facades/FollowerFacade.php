<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class FollowerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FollowerService';
    }
}