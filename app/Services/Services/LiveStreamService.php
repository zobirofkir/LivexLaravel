<?php

namespace App\Services\Services;

use App\Http\Requests\LiveStreamRequest;
use App\Http\Resources\LiveStreamResource;
use App\Models\LiveStream;
use App\Services\Constructors\LiveStreamConstructor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LiveStreamService implements LiveStreamConstructor
{
    /**
     * List Lives
     */
    public function index(): AnonymousResourceCollection
    {
        return LiveStreamResource::collection(
            LiveStream::with('user')->orderBy('created_at', 'desc')->get()
        );
    }

    /**
     * Store Live
     */
    public function store(LiveStreamRequest $request): LiveStreamResource
    {
        $data = $request->validated();
        
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        
        $stream = LiveStream::create(array_merge(
            $data,
            [
                'user_id' => Auth::id(),
                'stream_key' => Str::uuid(),
            ]
        ));

        return LiveStreamResource::make($stream);
    }

    /**
     * Show Live
     */
    public function show($id) : LiveStreamResource
    {
        $stream = LiveStream::with('user')->findOrFail($id);
        return LiveStreamResource::make($stream);
    }

    /**
     * Update Live
     */
    public function update(Request $request, $id) : LiveStreamResource
    {
        $stream = LiveStream::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'string',
            'is_live' => 'boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['title', 'is_live']);
        
        if ($request->hasFile('thumbnail')) {
            if ($stream->thumbnail) {
                Storage::disk('public')->delete($stream->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $stream->update($data);

        return LiveStreamResource::make($stream);
    }

    /**
     * Delete Live
     */
    public function destroy($id) : bool
    {
        $stream = LiveStream::where('user_id', Auth::id())->findOrFail($id);
        
        if ($stream->thumbnail) {
            Storage::disk('public')->delete($stream->thumbnail);
        }

        return $stream->delete();
    }
}