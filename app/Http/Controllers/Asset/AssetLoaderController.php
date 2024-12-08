<?php

namespace App\Http\Controllers\Asset;

use App\Http\Requests\Asset\StoreAssetLoaderRequest;
use App\Http\Requests\Asset\UpdateAssetLoaderRequest;
use App\Models\Asset\AssetLoader;
use App\Http\Controllers\Controller;

class AssetLoaderController extends Controller
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
    public function store(StoreAssetLoaderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetLoader $assetLoader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetLoader $assetLoader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetLoaderRequest $request, AssetLoader $assetLoader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetLoader $assetLoader)
    {
        //
    }
}
