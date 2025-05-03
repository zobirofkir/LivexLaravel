<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class EmailAuthFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EmailAuthService';
    }
}