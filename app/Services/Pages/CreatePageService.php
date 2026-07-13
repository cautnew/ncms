<?php

namespace App\Services\Pages;

use App\Models\Pages\Page;
use App\Models\Pages\PageVersion;
use Illuminate\Support\Str;

class CreatePageService
{
    public static function create(?array $data): Page
    {
        if (empty($data['name'])) {
            throw new \Exception('Name is required.', 422);
        }
        if (empty($data['website_id'])) {
            throw new \Exception('Website ID is required.', 422);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (Page::slugExists($data['slug'])) {
            throw new \Exception('Slug already exists.', 400);
        }

        $page = Page::create($data);
        $page->save();

        return $page;
    }

    public static function createPageVersion(Page $page, ?array $data): PageVersion
    {
        $pageVersion = PageVersion::create([
            'page_id' => $page->id,
            'name' => $data['name'] ?? $page->name,
            'is_current_editing' => $data['is_current_editing'] ?? false,
            'is_current' => $data['is_current'] ?? false,
        ]);
        $pageVersion->save();

        $page->pageVersion()->where('is_current', true)->update(['is_current' => false]);

        $page->page_version_id = $pageVersion->id;
        $page->save();

        return $pageVersion;
    }
}
