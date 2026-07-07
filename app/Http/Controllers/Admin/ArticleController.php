<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/articles/index', [
            'articles' => Article::query()
                ->orderByDesc('published_at')
                ->get(['id', 'slug', 'title', 'category', 'published_at']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/articles/create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        Article::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Article created.')]);

        return to_route('admin.articles.index');
    }

    public function edit(Article $article): Response
    {
        return Inertia::render('admin/articles/edit', [
            'article' => $article,
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Article updated.')]);

        return to_route('admin.articles.index');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Article deleted.')]);

        return to_route('admin.articles.index');
    }
}
