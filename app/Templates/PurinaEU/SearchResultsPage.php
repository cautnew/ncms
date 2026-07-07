<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use App\Models\Product;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\TAG;

class SearchResultsPage extends PurinaEUPage
{
    private string $query;

    public function __construct(string $query = '')
    {
        $this->query = trim($query);

        parent::__construct();
    }

    protected function pageTitle(): string
    {
        if (empty($this->query)) {
            return 'Busca';
        }

        return 'Resultados para "'.htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8').'"';
    }

    protected function metaDescription(): string
    {
        return 'Encontre artigos e produtos da Purina EU relacionados à sua busca.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        $results = $this->search();

        return new ArrayTAG([
            $this->buildSearchHeader(),
            empty($results) ? $this->buildEmptyState() : $this->buildResults($results),
        ]);
    }

    /**
     * @return array<int, array{type: string, href: string, title: string, snippet: string, image: string}>
     */
    private function search(): array
    {
        if (empty($this->query)) {
            return Article::query()
                ->orderByDesc('published_at')
                ->limit(4)
                ->get()
                ->map(fn (Article $article) => [
                    'type' => 'Artigo',
                    'href' => "/purinaeu/artigo/{$article->slug}",
                    'title' => $article->title,
                    'snippet' => $article->excerpt,
                    'image' => $article->image,
                ])
                ->all();
        }

        $needle = '%'.$this->query.'%';
        $results = [];

        $articles = Article::query()
            ->where(fn ($query) => $query->where('title', 'like', $needle)->orWhere('excerpt', 'like', $needle))
            ->get();

        foreach ($articles as $article) {
            $results[] = ['type' => 'Artigo', 'href' => "/purinaeu/artigo/{$article->slug}", 'title' => $article->title, 'snippet' => $article->excerpt, 'image' => $article->image];
        }

        $products = Product::query()
            ->where(fn ($query) => $query->where('name', 'like', $needle)->orWhere('description', 'like', $needle))
            ->get();

        foreach ($products as $product) {
            $results[] = ['type' => 'Produto', 'href' => "/purinaeu/produto/{$product->slug}", 'title' => $product->name, 'snippet' => $product->description, 'image' => $product->image];
        }

        return $results;
    }

    private function buildSearchHeader(): TAG
    {
        $header = TAG::div('search-header');
        $header->append(TAG::h1(null, 'Buscar'));

        $form = TAG::form(null, '/purinaeu/busca', 'get');
        $form->append(TAG::input(
            'header-search-input',
            null,
            'q',
            htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8'),
            'search',
            placeholder: 'Buscar artigos e produtos'
        ));
        $form->append(TAG::button('header-search-button', null, null, 'Buscar', null, 'submit'));
        $header->append($form);

        if (! empty($this->query)) {
            $count = count($this->search());
            $header->append(TAG::p('search-summary', null, "{$count} resultado(s) encontrado(s) para \"".htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8').'"'));
        }

        return $header;
    }

    /**
     * @param  array<int, array{type: string, href: string, title: string, snippet: string, image: string}>  $results
     */
    private function buildResults(array $results): TAG
    {
        $list = TAG::div('search-results');

        foreach ($results as $result) {
            $item = TAG::div('search-result-item');

            $item->append(
                TAG::a($result['href'], null, 'search-result-thumb')
                    ->append(TAG::img(null, null, null, src: $result['image'], alt: $result['title'])->allowContent(false))
            );

            $body = TAG::div(null);
            $body->append(TAG::span('tag', $result['type']));
            $body->append(TAG::h3('search-result-title')->append(TAG::a($result['href'], $result['title'])));
            $body->append(TAG::p(null, null, $result['snippet']));
            $item->append($body);

            $list->append($item);
        }

        $pagination = TAG::div('pagination');
        $pagination->append(TAG::a('#', '1', 'active'));
        $list->append($pagination);

        return $list;
    }

    private function buildEmptyState(): TAG
    {
        $empty = TAG::div('search-empty');
        $empty->append(TAG::h2(null, 'Nenhum resultado encontrado'));
        $empty->append(TAG::p(null, null, 'Tente buscar por outros termos, como "ração", "gatos" ou "nutrição".'));
        $empty->append(TAG::a('/purinaeu/artigos', 'Ver todos os artigos', 'btn'));

        return $empty;
    }
}
