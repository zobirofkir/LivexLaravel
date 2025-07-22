<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FollowerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'follower.service';
    }
}