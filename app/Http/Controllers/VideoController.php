<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return VideoResource::collection(
            Video::all()
        );
    }

    /**
     * Display the specified resource.
     * 
     * @return VideoResource
     */
    public function show(Video $video) : VideoResource
    {
        return VideoResource::make($video);
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
        return VideoResource::make(
            Video::create($request->validated())
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function update(VideoRequest $request, Video $video) : VideoResource
    {
        $video->update($request->validated());

        return VideoResource::make(
            $video->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Video $video
     * @return bool
     */
    public function destroy(Video $video) : bool
    {
        return $video->delete();
    }
    
}
