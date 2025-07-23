<?php

namespace App\Http\Controllers\api_v1\live;

use App\Http\Controllers\Controller;
use App\Http\Requests\LiveStreamRequest;
use App\Http\Resources\LiveStreamResource;
use Illuminate\Http\Request;
use App\Services\Facades\LiveStreamFacade;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LiveStreamController extends Controller
{
    /**
     * List All Lives
     */
    public function index() : AnonymousResourceCollection
    {
        return LiveStreamFacade::index();
    }

    /**
     * Store Live
     */
    public function store(LiveStreamRequest $request) : LiveStreamResource
    {
        return LiveStreamFacade::store($request);
    }

    /**
     * Show Single Live
     */
    public function show($id) : LiveStreamResource
    {
        return LiveStreamFacade::show($id);
    }

    /**
     * Update Live
     */
    public function update(Request $request, $id) : LiveStreamResource
    {
        return LiveStreamFacade::update($request, $id);
    }
    
    /**
     * Delete Live
     */
    public function destroy($id) : LiveStreamResource
    {
        return LiveStreamFacade::destroy($id);
    }

}
