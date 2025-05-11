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
use Illuminate\Support\Facades\Storage;

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
            Video::orderBy('id', 'desc')->get()
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
    public function store(VideoRequest $request): VideoResource
    {
        $data = $request->validated();
        $user = Auth::user();
    
        if (!empty($data['video_url'])) {
            if ($user->video_url && Storage::disk('public')->exists($user->video_url)) {
                Storage::disk('public')->delete($user->video_url);
            }
            $data['video_url'] = $data['video_url']->store('video_urls', 'public');
        }
    
        if (!empty($data['thumbnail'])) {
            if ($user->thumbnail && Storage::disk('public')->exists($user->thumbnail)) {
                Storage::disk('public')->delete($user->thumbnail);
            }
            $data['thumbnail'] = $data['thumbnail']->store('thumbnails', 'public');
        }
    
        $data['user_id'] = $user->id;
        
        return VideoResource::make(
            Video::create($data)
        );
    }
        
    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function update(VideoRequest $request, $id): VideoResource
    {
        $video = Video::findOrFail($id);
        $data = $request->validated();
    
        if (!empty($data['video_url'])) {
            if ($video->video_url && Storage::disk('public')->exists($video->video_url)) {
                Storage::disk('public')->delete($video->video_url);
            }
            $data['video_url'] = $data['video_url']->store('video_urls', 'public');
        }
    
        if (!empty($data['thumbnail'])) {
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $data['thumbnail']->store('thumbnails', 'public');
        }
    
        $video->update($data);
    
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