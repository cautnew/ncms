<?php

namespace App\Http\Controllers\Routes;

use App\Http\Controllers\Controller;
use App\Services\Routes\RouteCacheService;
use Inertia\Inertia;

class RoutesController extends Controller
{
  protected $routeCacheService;

  public function __construct(RouteCacheService $routeCacheService)
  {
    $this->routeCacheService = $routeCacheService;
  }

  public function index()
  {
    return Inertia::render('routes/routes', [
      'lastCachedAt' => $this->routeCacheService->getLastCachedAt()
    ]);
  }

  public function cache()
  {
    $this->routeCacheService->generate();
    return redirect()->back()->with('success', 'Cache de rotas gerado com sucesso!');
  }

  public function details()
  {
    return Inertia::render('routes/cache', [
        'isCached' => $this->routeCacheService->isCached(),
        'lastCachedAt' => $this->routeCacheService->getLastCachedAt()
    ]);
  }

  public function clear()
  {
    $this->routeCacheService->clear();
    return redirect()->back()->with('success', 'Cache de rotas limpo com sucesso!');
  }
}
