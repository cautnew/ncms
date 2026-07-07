<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\ASIDE;
use CN\PHTML\Core\TAG;

class ArticleListPage extends PurinaEUPage
{
    private ?string $categoria;

    public function __construct(?string $categoria = null)
    {
        $this->categoria = $categoria ?: null;

        parent::__construct();
    }

    protected function pageTitle(): string
    {
        return 'Artigos';
    }

    protected function metaDescription(): string
    {
        return 'Explore todos os artigos da Purina EU sobre nutrição, cuidados e comportamento de cães e gatos.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        $intro = PageContent::section('articles', 'intro', ['title' => '', 'text' => '']);

        return new ArrayTAG([
            TAG::h1(null, $intro['title']),
            TAG::p(null, null, $intro['text']),
            $this->buildCategoryFilters(),
            TAG::div('article-list-layout')
                ->append($this->buildArticleGrid())
                ->append($this->buildSidebar()),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function categories(): array
    {
        return Article::query()->distinct()->orderBy('category')->pluck('category')->all();
    }

    private function buildCategoryFilters(): TAG
    {
        $filters = TAG::div('category-filters');

        $allLink = TAG::a('/purinaeu/artigos', 'Todos');
        if (empty($this->categoria)) {
            $allLink->addClass('active');
        }
        $filters->append($allLink);

        foreach ($this->categories() as $category) {
            $link = TAG::a('/purinaeu/artigos?categoria='.rawurlencode($category), $category);
            if ($this->categoria === $category) {
                $link->addClass('active');
            }
            $filters->append($link);
        }

        return $filters;
    }

    private function buildArticleGrid(): TAG
    {
        $wrapper = TAG::div(null);

        $articles = Article::query()
            ->when($this->categoria, fn ($query) => $query->where('category', $this->categoria))
            ->orderByDesc('published_at')
            ->get();

        if ($articles->isEmpty()) {
            $wrapper->append(TAG::p(null, null, 'Nenhum artigo encontrado para esta categoria.'));

            return $wrapper;
        }

        $grid = TAG::div('grid grid-2');
        foreach ($articles as $article) {
            $grid->append($this->articleCard($article));
        }
        $wrapper->append($grid);

        $pagination = TAG::div('pagination');
        $pagination->append(TAG::a('#', '1', 'active'));
        $pagination->append(TAG::a('#', '2'));
        $pagination->append(TAG::a('#', 'Próxima »'));
        $wrapper->append($pagination);

        return $wrapper;
    }

    private function buildSidebar(): ASIDE
    {
        $aside = new ASIDE(null);

        $mostRead = TAG::div('sidebar-box');
        $mostRead->append(TAG::h3(null, 'Mais lidos'));
        $list = TAG::ul('sidebar-list');
        foreach (Article::query()->orderByDesc('published_at')->limit(4)->get() as $article) {
            $list->append(TAG::li(null)->append(TAG::a("/purinaeu/artigo/{$article['slug']}", $article['title'])));
        }
        $mostRead->append($list);
        $aside->append($mostRead);

        $categoriesBox = TAG::div('sidebar-box');
        $categoriesBox->append(TAG::h3(null, 'Categorias'));
        $categoryList = TAG::ul('sidebar-list');
        foreach ($this->categories() as $category) {
            $categoryList->append(
                TAG::li(null)->append(TAG::a('/purinaeu/artigos?categoria='.rawurlencode($category), $category))
            );
        }
        $categoriesBox->append($categoryList);
        $aside->append($categoriesBox);

        return $aside;
    }
}
