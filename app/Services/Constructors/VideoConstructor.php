<?php

namespace App\Services\Constructors;

use App\Http\Requests\VideoRequest;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface VideoConstructor
{
    /**
     * Display a listing of the resource.
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection;

    /**
     * Display the specified resource.
     * 
     * @return VideoResource
     */
    public function show($id): VideoResource;

    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function store(VideoRequest $request) : VideoResource;

    /**
     * Update the specified resource in storage.
     * 
     * @param VideoRequest $request
     * @param Video $video
     * @return VideoResource
     */
    public function update(VideoRequest $request, $id) : VideoResource;

        /**
     * Remove the specified resource from storage.
     * 
     * @param Video $video
     * @return bool
     */
    public function destroy($id) : bool;

}