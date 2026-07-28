<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Pages\Page;
use App\Services\Page\CreatePageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::joinAll();
        $response = [];
        foreach ($pages as $page) {
            $response[] = [
                ...$page->toArray(),
                'website_name' => $page->website_name ?? null,
                'layout_name' => $page->page_layout_name ?? null,
            ];
        }
        return Inertia::render('pages/index', ['page_list' => $response]);
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'slug' => 'string|nullable|max:255',
                'website_id' => 'required|uuid',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid input data.',
                'message' => $e->getMessage(),
            ], 422);
        }

        try {
            $newPage = CreatePageService::create($request->only(['name', 'slug', 'website_id']));
        } catch (\Exception $e) {
            $code = $e->getCode();
            if ($code < 400 || $code >= 600) {
                $code = 500;
            }

            return response()->json([
                'error' => 'Failed to create page.',
                'message' => $e->getMessage(),
            ], $code);
        }

        return response()->json(['new-page' => [
            'page-id' => $newPage->id,
            'slug' => $newPage->slug,
            'website-slug' => $newPage->website->slug,
            'message' => 'Page created successfully.',
        ]]);
    }

    public function update(Request $request)
    {
        return response()->json([
            'update-page' => [
                'message' => 'Page updated successfully.',
            ],
        ]);
    }

    public function list(Request $request)
    {
        $response = [];
        foreach (Page::get() as $page) {
            $response[] = [
                ...$page->toArray(),
                'layout-name' => $page->pageLayout?->name ?? null,
            ];
        }

        return response()->json(['page-list' => $response]);
    }
}
