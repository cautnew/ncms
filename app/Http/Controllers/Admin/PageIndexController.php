<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Inertia\Inertia;
use Inertia\Response;

class PageIndexController extends Controller
{
    private const PAGE_LABELS = [
        'home' => 'Home',
        'brand' => 'Marca',
        'faq' => 'FAQ',
        'articles' => 'Artigos',
    ];

    public function index(): Response
    {
        $pages = collect(self::PAGE_LABELS)->map(fn (string $label, string $page) => [
            'page' => $page,
            'label' => $label,
            'href' => route('admin.pages.blocks.index', ['pageContent' => PageContent::query()->firstOrCreate(['page' => $page])->getRouteKey()]),
        ])->values();

        return Inertia::render('admin/pages/index', [
            'pages' => $pages,
        ]);
    }
}
