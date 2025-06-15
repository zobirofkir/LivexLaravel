<?php

namespace App\Services\Services;

use App\Http\Requests\LiveStreamRequest;
use App\Http\Resources\LiveStreamResource;
use App\Models\LiveStream;
use App\Services\Constructors\LiveStreamConstructor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LiveStreamService implements LiveStreamConstructor
{
    /**
     * List Lives
     */
    public function index() : AnonymousResourceCollection
    {
        return LiveStreamResource::collection(
            LiveStream::where('is_live', true)->with('user')->get()
        );
    }

    /**
     * Store Live
     */
    public function store(LiveStreamRequest $request): LiveStreamResource
    {
        $stream = LiveStream::create(array_merge(
            $request->validated(),
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
        ]);

        $stream->update($request->only(['title', 'is_live']));

        return LiveStreamResource::make($stream);
    }

    /**
     * Delete Live
     */
    public function destroy($id) : bool
    {
        $stream = LiveStream::where('user_id', Auth::id())->findOrFail($id);

        return $stream->delete();

    }
}