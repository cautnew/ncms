<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pages\Page;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a paginated listing of pages.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $search = $request->query('search');

        $query = Page::query()->latest();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $pages = $query->paginate($perPage);

        return response()->json($pages);
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(StorePageRequest $request): JsonResponse
    {
        $page = Page::create($request->validated());

        return response()->json([
            'message' => 'Page created successfully',
            'data' => $page->fresh()
        ], 202);
    }

    /**
     * Display the specified page.
     */
    public function show($id): JsonResponse
    {
        $page = Page::findOrFail($id);

        return response()->json([
            'data' => $page
        ]);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(UpdatePageRequest $request, $id): JsonResponse
    {
        $page = Page::findOrFail($id);
        
        $page->update($request->validated());

        return response()->json([
            'message' => 'Page updated successfully',
            'data' => $page->fresh()
        ]);
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy($id): JsonResponse
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ]);
    }
}
