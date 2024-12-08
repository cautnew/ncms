<?php

namespace App\Http\Controllers\Finance;

use App\Http\Requests\Finance\StoreExpenseRequest;
use App\Http\Requests\Finance\UpdateExpenseRequest;
use App\Models\Finance\Expense;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
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
    public function store(StoreExpenseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expenses)
    {
        //
    }
}
