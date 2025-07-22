<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class EarningFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EarningService';
    }
}