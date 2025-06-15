<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LiveStreamRequest;
use Illuminate\Http\Request;
use App\Services\Facades\LiveStreamFacade;

class LiveStreamController extends Controller
{
    public function index()
    {
        return LiveStreamFacade::index();
    }

    public function store(LiveStreamRequest $request)
    {
        return LiveStreamFacade::store($request);
    }

    public function show($id)
    {
        return LiveStreamFacade::show($id);
    }

    public function update(Request $request, $id)
    {
        return LiveStreamFacade::update($request, $id);
    }
    
    public function destroy($id)
    {
        return LiveStreamFacade::destroy($id);
    }

}
