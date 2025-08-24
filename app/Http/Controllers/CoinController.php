<?php

namespace App\Http\Controllers;

use App\Services\Facades\CoinFacade;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CoinFacade::index();
    }
}
