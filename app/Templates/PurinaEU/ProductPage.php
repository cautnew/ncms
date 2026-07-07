<?php

namespace App\Templates\PurinaEU;

use App\Models\Product;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\DETAILS;
use CN\PHTML\Core\STRONG;
use CN\PHTML\Core\SUMMARY;
use CN\PHTML\Core\TAG;

class ProductPage extends PurinaEUPage
{
    private Product $product;

    public function __construct(?string $slug = null)
    {
        $this->product = Product::query()->where('slug', $slug)->first()
            ?? Product::query()->orderBy('name')->firstOrFail();

        parent::__construct();
    }

    protected function pageTitle(): string
    {
        return $this->product->name;
    }

    protected function metaDescription(): string
    {
        return $this->product->description;
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        return new ArrayTAG([
            $this->buildBreadcrumb(),
            $this->buildProductDetail(),
            $this->buildProductTabs(),
            $this->buildRelatedProducts(),
        ]);
    }

    private function buildBreadcrumb(): TAG
    {
        $breadcrumb = TAG::div('breadcrumb');
        $breadcrumb->append(TAG::a('/purinaeu', 'Início'));
        $breadcrumb->append(' / ');
        $breadcrumb->append(TAG::a('/purinaeu/produto', 'Produtos'));
        $breadcrumb->append(' / ');
        $breadcrumb->append($this->product['name']);

        return $breadcrumb;
    }

    private function buildProductDetail(): TAG
    {
        $gallery = TAG::div('product-gallery');
        $gallery->append(TAG::img(null, null, null, src: $this->product['image'], alt: $this->product['name'])->allowContent(false));

        $info = TAG::div('product-info');
        $info->append(TAG::span('tag', $this->product['category']));
        $info->append(TAG::h1(null, $this->product['name']));
        $info->append(TAG::div('product-rating', "★★★★★  {$this->product['rating']} ({$this->product['reviews']} avaliações)"));

        $priceRow = TAG::div('product-price-row');
        $priceRow->append(TAG::span('product-price', $this->product['price']));
        if (! empty($this->product['old_price'])) {
            $priceRow->append(TAG::span('product-price-old', $this->product['old_price']));
        }
        $info->append($priceRow);

        $info->append(TAG::p(null, null, $this->product['description']));

        $specsList = TAG::ul('product-specs');
        foreach ($this->product['specs'] as $label => $value) {
            $specsList->append(
                TAG::li(null)
                    ->append(TAG::span(null, $label))
                    ->append((new STRONG)->append($value))
            );
        }
        $info->append($specsList);

        $actions = TAG::div('product-actions');
        $actions->append(TAG::input('qty-input', null, 'quantity', '1', 'number', min: 1, max: 10));
        $actions->append(TAG::button('btn', null, null, 'Adicionar ao carrinho'));
        $info->append($actions);

        return TAG::div('product-detail')->append($gallery)->append($info);
    }

    private function buildProductTabs(): TAG
    {
        $wrapper = TAG::div('product-tabs');

        $wrapper->append(
            (new DETAILS('accordion-item', null, null, open: true))
                ->append(new SUMMARY(null, null, 'Descrição completa'))
                ->append(TAG::p(null, null, $this->product['description'].' Formulada para atender às necessidades nutricionais diárias, sem corantes artificiais.'))
        );

        $wrapper->append(
            (new DETAILS('accordion-item'))
                ->append(new SUMMARY(null, null, 'Modo de uso'))
                ->append(TAG::p(null, null, $this->product->usage_text))
        );

        $wrapper->append(
            (new DETAILS('accordion-item'))
                ->append(new SUMMARY(null, null, 'Ingredientes'))
                ->append(TAG::p(null, null, $this->product->ingredients_text))
        );

        return $wrapper;
    }

    private function buildRelatedProducts(): TAG
    {
        $section = TAG::section(null, 'produtos-relacionados');

        $title = TAG::div('section-title');
        $title->append(TAG::h2(null, 'Produtos relacionados'));
        $section->append($title);

        $grid = TAG::div('grid grid-4');
        $related = Product::query()
            ->where('slug', '!=', $this->product->slug)
            ->where('category', $this->product->category)
            ->limit(4)
            ->get();

        foreach ($related as $product) {
            $grid->append($this->productCard($product));
        }
        $section->append($grid);

        return $section;
    }
}
