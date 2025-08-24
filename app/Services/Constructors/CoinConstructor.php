<?php

namespace App\Services\Constructors;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface CoinConstructor
{
    /**
     * Display a listing of the resource.
     */
    public function index() : AnonymousResourceCollection;
}