<?php

namespace App\Http\Controllers\api_v1\actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Earning\StoreEarningRequest;
use App\Http\Resources\EarningResource;
use App\Services\Facades\EarningFacade;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $user = Auth::user();
        $earnings = EarningFacade::getEarningHistory($user);

        return EarningResource::collection($earnings);
    }

    public function store(StoreEarningRequest $request): EarningResource
    {
        $user = Auth::user();
        $earning = EarningFacade::addEarning($user, $request->amount, $request->source);

        return EarningResource::make($earning);
    }
}