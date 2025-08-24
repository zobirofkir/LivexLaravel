<?php

namespace App\Services\Services;

use App\Http\Resources\CoinResource;
use App\Models\Coin;
use App\Services\Constructors\CoinConstructor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CoinService implements CoinConstructor
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CoinResource::collection(
            Coin::all()
        );
    }
}