<?php

namespace App\Services\Constructors;

use App\Http\Requests\LiveStreamRequest;
use Illuminate\Http\Request;

interface LiveStreamConstructor
{
    public function index();

    public function store(LiveStreamRequest $request);

    public function show($id);

    public function update(Request $request, $id);
    
    public function destroy($id);
}