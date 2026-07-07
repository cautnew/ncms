<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateArticlesPageContentRequest;
use App\Http\Requests\Admin\UpdateBrandContentRequest;
use App\Http\Requests\Admin\UpdateFaqPageContentRequest;
use App\Http\Requests\Admin\UpdateHomeContentRequest;
use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PageContentController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('admin/pages/home', [
            'hero' => PageContent::section('home', 'hero', [
                'title' => '', 'subtitle' => '', 'button_label' => '', 'button_href' => '',
            ]),
            'info_blocks' => PageContent::section('home', 'info_blocks', []) ?: [['icon' => '', 'title' => '', 'text' => '']],
            'cta' => PageContent::section('home', 'cta', [
                'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
            ]),
        ]);
    }

    public function updateHome(UpdateHomeContentRequest $request): RedirectResponse
    {
        $this->save('home', $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Home page content updated.')]);

        return to_route('admin.pages.home');
    }

    public function brand(): Response
    {
        return Inertia::render('admin/pages/brand', [
            'hero' => PageContent::section('brand', 'hero', [
                'title' => '', 'subtitle' => '', 'button_label' => '', 'button_href' => '',
            ]),
            'story_history' => PageContent::section('brand', 'story_history', [
                'title' => '', 'text1' => '', 'text2' => '', 'image' => '',
            ]),
            'story_sustainability' => PageContent::section('brand', 'story_sustainability', [
                'title' => '', 'text' => '', 'image' => '',
            ]),
            'stats' => PageContent::section('brand', 'stats', []) ?: [['number' => '', 'label' => '']],
            'values' => PageContent::section('brand', 'values', []) ?: [['icon' => '', 'title' => '', 'text' => '']],
            'testimonial' => PageContent::section('brand', 'testimonial', ['quote' => '', 'author' => '']),
            'cta' => PageContent::section('brand', 'cta', [
                'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
            ]),
        ]);
    }

    public function updateBrand(UpdateBrandContentRequest $request): RedirectResponse
    {
        $this->save('brand', $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Brand page content updated.')]);

        return to_route('admin.pages.brand');
    }

    public function faq(): Response
    {
        return Inertia::render('admin/pages/faq', [
            'intro' => PageContent::section('faq', 'intro', ['title' => '', 'text' => '']),
            'cta' => PageContent::section('faq', 'cta', [
                'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
            ]),
        ]);
    }

    public function updateFaq(UpdateFaqPageContentRequest $request): RedirectResponse
    {
        $this->save('faq', $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ page content updated.')]);

        return to_route('admin.pages.faq');
    }

    public function articles(): Response
    {
        return Inertia::render('admin/pages/articles', [
            'intro' => PageContent::section('articles', 'intro', ['title' => '', 'text' => '']),
        ]);
    }

    public function updateArticles(UpdateArticlesPageContentRequest $request): RedirectResponse
    {
        $this->save('articles', $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Articles page content updated.')]);

        return to_route('admin.pages.articles');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function save(string $page, array $data): void
    {
        PageContent::query()->updateOrCreate(['page' => $page], ['data' => $data]);
    }
}
