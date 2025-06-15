<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class LiveStreamFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiveStreamService';
    }
}