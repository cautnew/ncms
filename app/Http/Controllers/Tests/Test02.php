<?php

namespace App\Http\Controllers\Tests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHTML\Core\TAG;
use PHTML\Templates\HTML5;

class Test02 extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $html = new HTML5();
        $html->setPageTitle('CautNew');
        $html->body()->append([TAG::p(html: 'Foi'), TAG::p(html: 'Foi2')]);

        return $html;
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
