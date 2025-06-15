<?php

namespace App\Services\Constructors;

use App\Http\Requests\LiveStreamRequest;
use App\Http\Resources\LiveStreamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface LiveStreamConstructor
{
    /**
     * Liste All Lives
     */
    public function index() : AnonymousResourceCollection;

    /**
     * Store Live
     */
    public function store(LiveStreamRequest $request) : LiveStreamResource;

    /**
     * Show Single Live
     */
    public function show($id) : LiveStreamResource;

    /**
     * Update Live
     */
    public function update(Request $request, $id) : LiveStreamResource;
    
    /**
     * Delete Live
     */
    public function destroy($id) : bool;
}