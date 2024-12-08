<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreditCardRequest;
use App\Http\Requests\UpdateCreditCardRequest;
use App\Models\Finance\CreditCard;

class CreditCardController extends Controller
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
    public function store(StoreCreditCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditCard $creditCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditCard $creditCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreditCardRequest $request, CreditCard $creditCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditCard $creditCard)
    {
        //
    }
}
