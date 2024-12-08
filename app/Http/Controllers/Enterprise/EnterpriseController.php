<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enterprise\Enterprise\StoreEnterpriseRequest;
use App\Http\Requests\Enterprise\Enterprise\UpdateEnterpriseRequest;
use App\Models\Enterprise\Enterprise;
use App\Models\Enterprise\EnterpriseManager;
use Inertia\Inertia;

class EnterpriseController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $enterprises = Enterprise::allAvaillable();
    return Inertia::render('Manager/Enterprises')
      ->with('enterprises', $enterprises->get());
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return Inertia::render('Manager/CreateEnterprise');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreEnterpriseRequest $request)
  {
    Enterprise::create($request->validated());
    $newEnterprise = Enterprise::findByName($request->name)->first();

    EnterpriseManager::create([
      'user_id' => $request->user()->id,
      'enterprise_id' => $newEnterprise->id,
      'is_active' => true
    ]);

    return redirect(route('ncms.manager.enterprises'));
  }

  /**
   * Display the specified resource.
   */
  public function show(Enterprise $enterprise)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id, Enterprise $enterprise)
  {
    return Inertia::render('Manager/EditEnterprise')
      ->with('enterprise', $enterprise->findById($id)->first());
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(string $id, UpdateEnterpriseRequest $request, Enterprise $enterprise)
  {
    $enterprise->findById($id)->update($request->validated());

    return Inertia::render('Manager/EditEnterprise')
      ->with('enterprise', $enterprise->findById($id)->first())
      ->with('enterpriseId', $id);
  }

  public function deleteConfirm(string $id, Enterprise $enterprise)
  {
    return Inertia::render('Manager/ConfirmDeleteEnterprise')
      ->with('enterprise', $enterprise->findById($id)->first());
  }

  public function delete(string $id, Enterprise $enterprise)
  {
    $enterprise->findById($id)->update([
      'is_active' => false
    ]);

    return redirect(route('ncms.manager.enterprises'))
      ->with('success', 'Empresa excluída com sucesso!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Enterprise $enterprise)
  {
    //
  }
}
