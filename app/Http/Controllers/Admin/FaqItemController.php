<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqItemRequest;
use App\Http\Requests\Admin\UpdateFaqItemRequest;
use App\Models\FaqCategory;
use App\Models\FaqItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FaqItemController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/faq-items/index', [
            'items' => FaqItem::query()->with('category:id,name')->orderBy('order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/faq-items/create', [
            'categories' => FaqCategory::query()->orderBy('order')->get(['id', 'name']),
        ]);
    }

    public function store(StoreFaqItemRequest $request): RedirectResponse
    {
        FaqItem::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ question created.')]);

        return to_route('admin.faq-items.index');
    }

    public function edit(FaqItem $faqItem): Response
    {
        return Inertia::render('admin/faq-items/edit', [
            'item' => $faqItem,
            'categories' => FaqCategory::query()->orderBy('order')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateFaqItemRequest $request, FaqItem $faqItem): RedirectResponse
    {
        $faqItem->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ question updated.')]);

        return to_route('admin.faq-items.index');
    }

    public function destroy(FaqItem $faqItem): RedirectResponse
    {
        $faqItem->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('FAQ question deleted.')]);

        return to_route('admin.faq-items.index');
    }
}
