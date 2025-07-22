<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class UserServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UserServiceService';
    }
}