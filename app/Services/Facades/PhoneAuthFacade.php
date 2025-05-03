<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class PhoneAuthFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PhoneAuthService';
    }
}