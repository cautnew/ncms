<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateValidationController extends Controller
{
  public function validateClass(Request $request)
  {
    $className = $request->input('class');

    if (class_exists($className)) {
      return response()->json([
        'valid' => true,
        'class' => $className
      ]);
    }

    return response()->json([
      'valid' => false,
      'class' => $className
    ], 422);
  }
}
