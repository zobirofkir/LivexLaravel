<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Earning\StoreEarningRequest;
use App\Services\Facades\EarningFacade;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $earnings = EarningFacade::getEarningHistory($user);
        $totalEarnings = EarningFacade::getTotalEarnings($user);

        return response()->json([
            'earnings' => $earnings,
            'total_earnings' => $totalEarnings,
        ]);
    }

    public function store(StoreEarningRequest $request)
    {
        $user = Auth::user();
        $earning = EarningFacade::addEarning($user, $request->amount, $request->source);

        return response()->json($earning, 201);
    }
}