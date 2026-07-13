<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Pages\Page;
use App\Services\Pages\CreatePageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
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
        
    }

    public function list(Request $request)
    {
        $pages = Page::all();

        return response()->json(['page-list' => $pages]);
    }
}
