<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class AuthUserFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AuthUserService';
    }
}