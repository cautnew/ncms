<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PagesController extends Controller
{
  public function index()
  {
    return Inertia::render('pages/pages');
  }

  public function create()
  {
    $fields = [
        [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Page Title',
            'maxLength' => 130,
            'alertAt' => 80,
        ],
        [
            'type' => 'textarea',
            'name' => 'description',
            'label' => 'Page Description',
            'maxLength' => 270,
        ],
        [
            'type' => 'image',
            'name' => 'featured_image',
            'label' => 'Featured Image',
        ],
    ];

    return Inertia::render('pages/create', [
        'fields' => $fields,
    ]);
  }

  public function store(Request $request)
  {
    return Inertia::render('pages/pages');
  }

  public function show($id)
  {
    return Inertia::render('pages/pages');
  }

  public function edit($id)
  {
    return Inertia::render('pages/pages');
  }

  public function update(Request $request, $id)
  {
    return Inertia::render('pages/pages');
  }

  public function destroy($id)
  {
    return Inertia::render('pages/pages');
  }
}
