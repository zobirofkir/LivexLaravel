<?php

namespace App\Http\Controllers\api_v1\live;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Services\Facades\VideoFacade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return VideoFacade::index();
    }

    /**
     * Display the specified resource.
     * 
     * @return VideoResource
     */
    public function show($id): VideoResource
    {
        return VideoFacade::show($id);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function store(VideoRequest $request) : VideoResource
    {
        return VideoFacade::store($request);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function update(VideoRequest $request, $id) : VideoResource
    {
        return VideoFacade::update($request, $id);
    }

        /**
     * Remove the specified resource from storage.
     * 
     * @param Video $video
     * @return bool
     */
    public function destroy($id) : bool
    {
        return VideoFacade::destroy($id);
    }

}
