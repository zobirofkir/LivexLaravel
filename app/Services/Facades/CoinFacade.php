<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class CoinFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CoinService';
    }
}