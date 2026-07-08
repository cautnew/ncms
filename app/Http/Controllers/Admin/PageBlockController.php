<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\HasContentBlocks;
use App\Http\Controllers\Admin\Concerns\ManagesContentBlocks;
use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class PageBlockController extends Controller
{
    use ManagesContentBlocks;

    private const BASE_TYPES = ['hero', 'banner', 'image', 'text', 'highlight', 'stat', 'testimonial'];

    private const PAGE_LABELS = [
        'home' => 'Home',
        'brand' => 'Marca',
        'faq' => 'FAQ',
        'articles' => 'Artigos',
    ];

    public function index(PageContent $pageContent): Response
    {
        return $this->doIndex($pageContent);
    }

    public function create(PageContent $pageContent, Request $request): Response
    {
        return $this->doCreate($pageContent, $request);
    }

    public function store(PageContent $pageContent, Request $request): RedirectResponse
    {
        return $this->doStore($pageContent, $request);
    }

    public function edit(PageContent $pageContent, ContentBlock $block): Response
    {
        return $this->doEdit($pageContent, $block);
    }

    public function update(PageContent $pageContent, ContentBlock $block, Request $request): RedirectResponse
    {
        return $this->doUpdate($pageContent, $block, $request);
    }

    public function destroy(PageContent $pageContent, ContentBlock $block): RedirectResponse
    {
        return $this->doDestroy($pageContent, $block);
    }

    public function moveUp(PageContent $pageContent, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveUp($pageContent, $block);
    }

    public function moveDown(PageContent $pageContent, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveDown($pageContent, $block);
    }

    /**
     * @return array<int, string>
     */
    protected function allowedTypes(HasContentBlocks&Model $owner): array
    {
        /** @var PageContent $owner */
        return match ($owner->page) {
            'home' => [...self::BASE_TYPES, 'articles_grid'],
            'faq' => [...self::BASE_TYPES, 'faq_list'],
            default => self::BASE_TYPES,
        };
    }

    protected function routeParamName(): string
    {
        return 'pageContent';
    }

    protected function routeBase(): string
    {
        return 'admin.pages.blocks';
    }

    /**
     * @return array<string, mixed>
     */
    protected function ownerProps(HasContentBlocks&Model $owner): array
    {
        /** @var PageContent $owner */
        return [
            'owner_label' => self::PAGE_LABELS[$owner->page] ?? $owner->page,
            'back_href' => route('admin.pages.index'),
            'back_label' => 'Páginas',
        ];
    }
}
