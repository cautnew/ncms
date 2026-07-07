<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/products/index', [
            'products' => Product::query()
                ->orderBy('name')
                ->get(['id', 'slug', 'name', 'category', 'price']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/products/create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::query()->create($this->withSpecs($request->validated()));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product created.')]);

        return to_route('admin.products.index');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('admin/products/edit', [
            'product' => [
                ...$product->toArray(),
                'specs' => collect($product->specs ?? [])
                    ->map(fn ($value, $label) => ['label' => $label, 'value' => $value])
                    ->values(),
            ],
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->withSpecs($request->validated()));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product updated.')]);

        return to_route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Product deleted.')]);

        return to_route('admin.products.index');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withSpecs(array $data): array
    {
        /** @var array<int, array{label: string, value: string}> $specs */
        $specs = $data['specs'];

        $data['specs'] = collect($specs)->mapWithKeys(fn (array $row) => [$row['label'] => $row['value']])->all();

        return $data;
    }
}
