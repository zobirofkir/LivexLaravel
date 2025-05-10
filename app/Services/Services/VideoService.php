<?php

namespace App\Services\Services;

use App\Services\Constructors\VideoConstructor;

use App\Http\Requests\VideoRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class VideoService implements VideoConstructor
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
    public function show($id): VideoResource
    {
        $video = Video::findOrFail($id);
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
            Video::create(array_merge(
                $request->validated(),
                ['user_id' => Auth::user()->id]
            ))
        );
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
        $video = Video::findOrFail($id);

        $video->update(
            array_merge(
                $request->validated(),
                ['user_id' => $video->user_id]
            )
        );
    
        return VideoResource::make($video->refresh());
    }
    
    /**
     * Remove the specified resource from storage.
     * 
     * @param Video $video
     * @return bool
     */
    public function destroy($id) : bool
    {
        $video = Video::findOrFail($id);
        return $video->delete();
    }
    
}