<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\Pages\Metadata\StoreMetadataRequest;
use App\Http\Requests\Pages\Metadata\UpdateMetadataRequest;
use App\Models\Pages\Metadata;

class MetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMetadataRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Metadata $metadata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Metadata $metadata)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMetadataRequest $request, Metadata $metadata)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Metadata $metadata)
    {
        //
    }
}
