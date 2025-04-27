<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
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

    public function store()
    {
        return Inertia::render('ponto/register');
    }
}
