<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\PontoStoreRequest;
use App\Models\HR\Ponto;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PontoController extends Controller
{
    public function index()
    {
        return Inertia::render('ponto/ponto');
    }

    public function create()
    {
        return Inertia::render('ponto/register');
    }

    public function store(PontoStoreRequest $request)
    {
        try {
            $request->validated();
            $data = [
                'photo_code' => $request->photo,
                'coord_latitude' => $request->latitude,
                'coord_longitude' => $request->longitude
            ];
            Ponto::create($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => ['message' => $e->getMessage()]
            ]);
        }

        return response()->json([
            'foi' => 'foim'
        ]);
    }
}
