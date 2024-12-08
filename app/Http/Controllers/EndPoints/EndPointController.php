<?php

namespace App\Http\Controllers\EndPoints;

use App\Http\Requests\EndPoints\StoreEndPointRequest;
use App\Http\Requests\EndPoints\UpdateEndPointRequest;
use App\Models\EndPoints\EndPoint;
use App\Http\Controllers\Controller;

class EndPointController extends Controller
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
  public function store(StoreEndPointRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(EndPoint $endPoint)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(EndPoint $endPoint)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateEndPointRequest $request, EndPoint $endPoint)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(EndPoint $endPoint)
  {
    //
  }
}
