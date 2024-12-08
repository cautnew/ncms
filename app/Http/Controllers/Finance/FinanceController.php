<?php

namespace App\Http\Controllers\Finance;

use App\Http\Requests\Finance\StoreFundRequest;
use App\Http\Requests\Finance\UpdateFundRequest;
use App\Http\Controllers\Controller;
use App\Models\Finance\Fund;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinanceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    return Inertia::render('Financeiro/Financeiro')
      ->with('funds', Fund::all()->where('user_id', $request->user()->id));
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
  public function store(StoreFundRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Fund $fund)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Fund $fund)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateFundRequest $request, Fund $fund)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Fund $fund)
  {
    //
  }
}
