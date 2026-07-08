<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Contracts\HasContentBlocks;
use App\Models\ContentBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Logica de CRUD + reordenacao de ContentBlock compartilhada pelos
 * controllers de blocos de Paginas, Artigos e Produtos. Cada controller
 * concreto so precisa resolver o "owner" (o model polimorfico dono dos
 * blocos) via route model binding e informar os tipos de bloco permitidos
 * e os nomes de rota usados para montar URLs.
 */
trait ManagesContentBlocks
{
    /**
     * @return array<int, string>
     */
    abstract protected function allowedTypes(HasContentBlocks&Model $owner): array;

    abstract protected function routeParamName(): string;

    abstract protected function routeBase(): string;

    /**
     * @return array<string, mixed>
     */
    abstract protected function ownerProps(HasContentBlocks&Model $owner): array;

    protected function doIndex(HasContentBlocks&Model $owner): Response
    {
        $blocks = $owner->blocks()->get();

        return Inertia::render('admin/blocks/index', [
            ...$this->ownerProps($owner),
            'blocks' => $blocks->values()->map(fn (ContentBlock $block, int $index) => [
                'id' => $block->id,
                'type' => $block->type,
                'type_label' => ContentBlock::labelFor($block->type),
                'summary' => $block->summary(),
                'is_first' => $index === 0,
                'is_last' => $index === $blocks->count() - 1,
                'edit_url' => $this->blockUrl($owner, $block, 'edit'),
                'move_up_url' => $this->blockUrl($owner, $block, 'moveUp'),
                'move_down_url' => $this->blockUrl($owner, $block, 'moveDown'),
                'delete_url' => $this->blockUrl($owner, $block, 'destroy'),
            ]),
            'add_links' => collect($this->allowedTypes($owner))->map(fn (string $type) => [
                'type' => $type,
                'label' => ContentBlock::labelFor($type),
                'href' => route($this->routeBase().'.create', [$this->routeParamName() => $owner->getRouteKey(), 'type' => $type]),
            ])->values(),
        ]);
    }

    protected function doCreate(HasContentBlocks&Model $owner, Request $request): Response
    {
        $type = $request->string('type')->value();
        abort_unless(in_array($type, $this->allowedTypes($owner), true), 404);

        return Inertia::render('admin/blocks/form', [
            ...$this->ownerProps($owner),
            'index_href' => route($this->routeBase().'.index', [$this->routeParamName() => $owner->getRouteKey()]),
            'type' => $type,
            'type_label' => ContentBlock::labelFor($type),
            'fields' => ContentBlock::fieldsFor($type),
            'block' => null,
            'action' => route($this->routeBase().'.store', [$this->routeParamName() => $owner->getRouteKey()]),
            'method' => 'post',
        ]);
    }

    protected function doStore(HasContentBlocks&Model $owner, Request $request): RedirectResponse
    {
        $type = (string) $request->input('type');
        abort_unless(in_array($type, $this->allowedTypes($owner), true), 404);

        $validated = Validator::make($request->all(), [
            'type' => 'required|string',
            ...ContentBlock::rulesFor($type),
        ])->validate();

        $nextOrder = ((int) $owner->blocks()->max('order')) + 1;

        $owner->blocks()->create([
            'type' => $type,
            'data' => $validated['data'] ?? [],
            'order' => $nextOrder,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Block created.')]);

        return $this->indexRedirect($owner);
    }

    protected function doEdit(HasContentBlocks&Model $owner, ContentBlock $block): Response
    {
        $this->assertOwnership($owner, $block);

        return Inertia::render('admin/blocks/form', [
            ...$this->ownerProps($owner),
            'index_href' => route($this->routeBase().'.index', [$this->routeParamName() => $owner->getRouteKey()]),
            'type' => $block->type,
            'type_label' => ContentBlock::labelFor($block->type),
            'fields' => ContentBlock::fieldsFor($block->type),
            'block' => ['id' => $block->id, 'data' => $block->data],
            'action' => route($this->routeBase().'.update', [$this->routeParamName() => $owner->getRouteKey(), 'block' => $block->id]),
            'method' => 'put',
        ]);
    }

    protected function doUpdate(HasContentBlocks&Model $owner, ContentBlock $block, Request $request): RedirectResponse
    {
        $this->assertOwnership($owner, $block);

        $validated = Validator::make($request->all(), ContentBlock::rulesFor($block->type))->validate();

        $block->update(['data' => $validated['data'] ?? []]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Block updated.')]);

        return $this->indexRedirect($owner);
    }

    protected function doDestroy(HasContentBlocks&Model $owner, ContentBlock $block): RedirectResponse
    {
        $this->assertOwnership($owner, $block);

        $block->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Block deleted.')]);

        return $this->indexRedirect($owner);
    }

    protected function doMoveUp(HasContentBlocks&Model $owner, ContentBlock $block): RedirectResponse
    {
        $this->assertOwnership($owner, $block);

        $previous = $owner->blocks()->reorder('order', 'desc')->where('order', '<', $block->order)->first();

        if ($previous) {
            $currentOrder = $block->order;
            $block->update(['order' => $previous->order]);
            $previous->update(['order' => $currentOrder]);
        }

        return $this->indexRedirect($owner);
    }

    protected function doMoveDown(HasContentBlocks&Model $owner, ContentBlock $block): RedirectResponse
    {
        $this->assertOwnership($owner, $block);

        $next = $owner->blocks()->reorder('order', 'asc')->where('order', '>', $block->order)->first();

        if ($next) {
            $currentOrder = $block->order;
            $block->update(['order' => $next->order]);
            $next->update(['order' => $currentOrder]);
        }

        return $this->indexRedirect($owner);
    }

    protected function indexRedirect(HasContentBlocks&Model $owner): RedirectResponse
    {
        return redirect()->route($this->routeBase().'.index', [$this->routeParamName() => $owner->getRouteKey()]);
    }

    private function blockUrl(HasContentBlocks&Model $owner, ContentBlock $block, string $action): string
    {
        return route($this->routeBase().'.'.$action, [$this->routeParamName() => $owner->getRouteKey(), 'block' => $block->id]);
    }

    private function assertOwnership(HasContentBlocks&Model $owner, ContentBlock $block): void
    {
        abort_unless(
            $block->blockable_type === $owner::class && $block->blockable_id === $owner->getKey(),
            404
        );
    }
}
