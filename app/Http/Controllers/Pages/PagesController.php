<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Pages\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagesController extends Controller
{
  private array $fields = [
    [
      'type' => 'text',
      'name' => 'title',
      'label' => 'Page Title',
      'maxLength' => 130,
      'alertAt' => 80,
    ],
    [
      'type' => 'select',
      'name' => 'type_id',
      'label' => 'Page Type',
      'options' => [],
    ],
    [
      'type' => 'textarea',
      'name' => 'description',
      'label' => 'Page Description',
      'rows' => 3,
      'maxLength' => 270,
    ],
    [
      'type' => 'image',
      'name' => 'featured_image',
      'label' => 'Featured Image',
    ],
  ];

  public function index()
  {
    return Inertia::render('pages/index');
  }

  public function create()
  {
    $pageTypes = Type::all()->map(fn($type) => [
      'value' => $type->id,
      'label' => $type->name
    ])->toArray();

    $fields = $this->fields;
    $fields[1]['options'] = $pageTypes;

    return Inertia::render('pages/create', [
      'fields' => $fields,
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:130',
      'type_id' => 'required|string|exists:page_types,id',
      'description' => 'nullable|string|max:270',
      'featured_image_file' => 'nullable|image|max:2048',
      'featured_image_desc' => 'nullable|string|max:255',
    ]);

    if ($request->hasFile('featured_image_file')) {
      $path = $request->file('featured_image_file')->store('pages', 'public');
    }

    file_put_contents(__DIR__ . '/pages.json', json_encode([
      ...$request->all(),
      'featured_image' => $path ?? null,
    ]));

    return back()->with('success', 'O processo foi bem sucedido.');
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
