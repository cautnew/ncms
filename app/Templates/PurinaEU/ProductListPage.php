<?php

namespace App\Templates\PurinaEU;

use App\Models\Product;
use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\ASIDE;
use CN\PHTML\Core\TAG;

class ProductListPage extends PurinaEUPage
{
    private ?string $categoria;

    public function __construct(?string $categoria = null)
    {
        $this->categoria = $categoria ?: null;

        parent::__construct();
    }

    protected function pageTitle(): string
    {
        return 'Produtos';
    }

    protected function metaDescription(): string
    {
        return 'Explore todos os produtos da Purina EU para cães e gatos.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        $productsPage = PageContent::query()->where('page', 'products')->firstOrFail();

        return new ArrayTAG([
            $this->renderBlocks($productsPage->blocks),
            $this->buildCategoryFilters(),
            TAG::div('product-list-layout')
                ->append($this->buildProductGrid())
                ->append($this->buildSidebar()),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function categories(): array
    {
        return Product::query()->distinct()->orderBy('category')->pluck('category')->all();
    }

    private function buildCategoryFilters(): TAG
    {
        $filters = TAG::div('category-filters');

        $allLink = TAG::a('/purinaeu/produtos', 'Todos');
        if (empty($this->categoria)) {
            $allLink->addClass('active');
        }
        $filters->append($allLink);

        foreach ($this->categories() as $category) {
            $link = TAG::a('/purinaeu/produtos?categoria='.rawurlencode($category), $category);
            if ($this->categoria === $category) {
                $link->addClass('active');
            }
            $filters->append($link);
        }

        return $filters;
    }

    private function buildProductGrid(): TAG
    {
        $wrapper = TAG::div(null);

        $products = Product::query()
            ->when($this->categoria, fn ($query) => $query->where('category', $this->categoria))
            ->orderByDesc('published_at')
            ->get();

        if ($products->isEmpty()) {
            $wrapper->append(TAG::p(null, null, 'Nenhum produto encontrado para esta categoria.'));

            return $wrapper;
        }

        $grid = TAG::div('grid grid-2');
        foreach ($products as $product) {
            $grid->append($this->ProductCard($product));
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
        foreach (Product::query()->orderByDesc('published_at')->limit(4)->get() as $Product) {
            $list->append(TAG::li(null)->append(TAG::a("/purinaeu/produto/{$Product['slug']}", $Product['title'])));
        }
        $mostRead->append($list);
        $aside->append($mostRead);

        $categoriesBox = TAG::div('sidebar-box');
        $categoriesBox->append(TAG::h3(null, 'Categorias'));
        $categoryList = TAG::ul('sidebar-list');
        foreach ($this->categories() as $category) {
            $categoryList->append(
                TAG::li(null)->append(TAG::a('/purinaeu/produtos?categoria='.rawurlencode($category), $category))
            );
        }
        $categoriesBox->append($categoryList);
        $aside->append($categoriesBox);

        return $aside;
    }
}
