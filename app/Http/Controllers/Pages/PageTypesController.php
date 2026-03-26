<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Pages\Page;
use App\Models\Pages\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PageTypesController extends Controller
{
  private array $fields = [
    [
      'type' => 'text',
      'name' => 'name',
      'label' => 'Page Type Name',
      'maxLength' => 100,
    ],
    [
      'type' => 'text',
      'name' => 'slug',
      'label' => 'Slug',
      'maxLength' => 20,
    ],
    [
      'type' => 'text',
      'name' => 'version',
      'label' => 'Version',
      'maxLength' => 10,
    ],
    [
      'type' => 'textarea',
      'name' => 'description',
      'label' => 'Description',
      'rows' => 3,
      'maxLength' => 255,
    ],
  ];

  public function index()
  {
    $page_types = Type::all();

    return Inertia::render('page-types/index', [
      'page_types' => $page_types,
    ]);
  }

  public function create()
  {
    return Inertia::render('page-types/create', [
      'fields' => $this->fields,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:100|unique:page_types,name',
      'slug' => 'required|string|max:20|unique:page_types,slug',
      'version' => 'required|string|max:10',
      'description' => 'nullable|string|max:255',
    ]);

    try {
      Type::create([
        'name' => $validated['name'],
        'slug' => $validated['slug'],
        'version' => $validated['version'],
        'description' => $validated['description'],
      ]);
    } catch (\Exception $e) {
      return back()->with('error', 'Page Type creation failed.');
    }

    return back()->with('success', 'Page Type created successfully.');
  }

  public function show($id)
  {
    $page_type = Type::findOrFail($id);
    
    return Inertia::render('page-types/show', [
      'page_type' => $page_type,
    ]);
  }

  public function edit($id)
  {
    $page_type = Type::findOrFail($id);

    return Inertia::render('page-types/edit', [
      'page_type' => $page_type,
      'fields' => $this->fields,
    ]);
  }

  public function update(Request $request, $id)
  {
    $page_type = Type::findOrFail($id);

    $validated = $request->validate([
      'name' => 'required|string|max:100|unique:page_types,name,' . $id,
      'slug' => 'required|string|max:20|unique:page_types,slug,' . $id,
      'version' => 'required|string|max:10',
      'description' => 'nullable|string|max:255',
    ]);
    
    $page_type->update($validated);

    return back()->with('success', 'Page Type updated successfully.');
  }

  public function pages($id)
  {
    $pages = \App\Models\Pages\Page::where('type_id', $id)->get();
    return response()->json($pages);
  }
}
