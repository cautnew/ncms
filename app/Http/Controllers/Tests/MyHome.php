<?php

namespace App\Http\Controllers\Tests;

use App\Models\Fin\Cotacoes\CotacaoFundamentusAcao;
use App\Templates\Extension\Blog\TemplateBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Env;
use NumberFormatter;
use PHTML\Core\TAG;
use PHTML\Templates\HTML5;

class MyHome extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $template = new TemplateBlog();
        $template->definePageTitle("My Home Page");
        $template->appendToMain(TAG::p(append: [
            TAG::a('#', 'Lista de empresas bem ranqueadas')
        ]));

        return $template->getPage();
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
