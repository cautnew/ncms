<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqCategoryRequest;
use App\Http\Requests\Admin\UpdateFaqCategoryRequest;
use App\Models\FaqCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FaqCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/faq-categories/index', [
            'categories' => FaqCategory::query()->withCount('items')->orderBy('order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/faq-categories/create');
    }

    public function store(StoreFaqCategoryRequest $request): RedirectResponse
    {
        FaqCategory::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ category created.')]);

        return to_route('admin.faq-categories.index');
    }

    public function edit(FaqCategory $faqCategory): Response
    {
        return Inertia::render('admin/faq-categories/edit', [
            'category' => $faqCategory,
        ]);
    }

    public function update(UpdateFaqCategoryRequest $request, FaqCategory $faqCategory): RedirectResponse
    {
        $faqCategory->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ category updated.')]);

        return to_route('admin.faq-categories.index');
    }

    public function destroy(FaqCategory $faqCategory): RedirectResponse
    {
        $faqCategory->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ category deleted.')]);

        return to_route('admin.faq-categories.index');
    }
}
