<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\HasContentBlocks;
use App\Http\Controllers\Admin\Concerns\ManagesContentBlocks;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ContentBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ArticleBlockController extends Controller
{
    use ManagesContentBlocks;

    public function index(Article $article): Response
    {
        return $this->doIndex($article);
    }

    public function create(Article $article, Request $request): Response
    {
        return $this->doCreate($article, $request);
    }

    public function store(Article $article, Request $request): RedirectResponse
    {
        return $this->doStore($article, $request);
    }

    public function edit(Article $article, ContentBlock $block): Response
    {
        return $this->doEdit($article, $block);
    }

    public function update(Article $article, ContentBlock $block, Request $request): RedirectResponse
    {
        return $this->doUpdate($article, $block, $request);
    }

    public function destroy(Article $article, ContentBlock $block): RedirectResponse
    {
        return $this->doDestroy($article, $block);
    }

    public function moveUp(Article $article, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveUp($article, $block);
    }

    public function moveDown(Article $article, ContentBlock $block): RedirectResponse
    {
        return $this->doMoveDown($article, $block);
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
        return 'article';
    }

    protected function routeBase(): string
    {
        return 'admin.articles.blocks';
    }

    /**
     * @return array<string, mixed>
     */
    protected function ownerProps(HasContentBlocks&Model $owner): array
    {
        /** @var Article $owner */
        return [
            'owner_label' => $owner->title,
            'back_href' => route('admin.articles.edit', ['article' => $owner->getRouteKey()]),
            'back_label' => 'Editar artigo',
        ];
    }
}
