<?php

namespace App\Http\Controllers\Curriculo;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurriculoRequest;
use App\Http\Requests\UpdateCurriculoRequest;
use App\Models\Curriculo\Curriculo;
use Inertia\Inertia;

class CurriculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('curriculo/curriculos');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('curriculo/addcurriculo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurriculoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Curriculo $curriculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculo $curriculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurriculoRequest $request, Curriculo $curriculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculo $curriculo)
    {
        //
    }
}
