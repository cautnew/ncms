<?php

namespace App\Http\Controllers\Routes;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RoutesController extends Controller
{
  public function index()
  {
    return Inertia::render('routes/routes');
  }
}
