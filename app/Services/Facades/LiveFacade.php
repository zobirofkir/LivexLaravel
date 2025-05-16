<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class LiveFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiveService';
    }
}