<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\HasContentBlocks;
use App\Http\Controllers\Admin\Concerns\ManagesContentBlocks;
use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ProductBlockController extends Controller
{
    use ManagesContentBlocks;

    public function index(Product $product): Response
    {
        return $this->doIndex($product);
    }

    public function create(Product $product, Request $request): Response
    {
        return $this->doCreate($product, $request);
    }

    public function store(Product $product, Request $request): RedirectResponse
    {
        return $this->doStore($product, $request);
    }

    public function edit(Product $product, ContentBlock $block): Response
    {
        return $this->doEdit($product, $block);
    }

    public function update(Product $product, ContentBlock $block, Request $request): RedirectResponse
    {
        return $this->doUpdate($product, $block, $request);
    }

    public function destroy(Product $product, ContentBlock $block): RedirectResponse
    {
        return $this->doDestroy($product, $block);
    }

    public function moveUp(Product $product, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveUp($product, $block);
    }

    public function moveDown(Product $product, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveDown($product, $block);
    }

    /**
     * @return array<int, string>
     */
    protected function allowedTypes(HasContentBlocks&Model $owner): array
    {
        return ['text', 'image', 'banner'];
    }

    protected function routeParamName(): string
    {
        return 'product';
    }

    protected function routeBase(): string
    {
        return 'admin.products.blocks';
    }

    /**
     * @return array<string, mixed>
     */
    protected function ownerProps(HasContentBlocks&Model $owner): array
    {
        /** @var Product $owner */
        return [
            'owner_label' => $owner->name,
            'back_href' => route('admin.products.edit', ['product' => $owner->getRouteKey()]),
            'back_label' => 'Editar produto',
        ];
    }
}
