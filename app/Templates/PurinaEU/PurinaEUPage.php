<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use App\Models\Product;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\TAG;
use CN\PHTML\Templates\HTML5;

/**
 * Layout base compartilhado por todos os templates do site PurinaEU:
 * cabecalho com logo e menu com submenus flutuantes, rodape e assets comuns.
 */
abstract class PurinaEUPage extends HTML5
{
    public function __construct()
    {
        $this->preRenderHtml(function (HTML5 $page) {
            $page->setPageTitle($this->pageTitle().' | Purina EU');
            $page->appendToHead(TAG::meta('viewport', content: 'width=device-width, initial-scale=1'));
            $page->appendToHead(TAG::meta('description', content: $this->metaDescription()));
            $page->appendToHead(TAG::link($this->assetsPath().'/css/geral.css', 'stylesheet'));
            $page->appendToHead(TAG::script($this->assetsPath().'/js/topmenu.js'));

            $page->appendToBody($this->buildHeader());
            $page->appendToBody(
                TAG::main('page-content')->append($this->buildContent())
            );
            $page->appendToBody($this->buildFooter());
        });
    }

    protected function assetsPath(): string
    {
        return '/templates/purinaeu/assets';
    }

    abstract protected function pageTitle(): string;

    abstract protected function metaDescription(): string;

    /**
     * @return TAG|ArrayTAG|string conteudo especifico de cada template.
     */
    abstract protected function buildContent(): TAG|ArrayTAG|string;

    protected function menuItems(): array
    {
        return [
            [
                'label' => 'Início',
                'href' => '/purinaeu',
            ],
            [
                'label' => 'Produtos',
                'href' => '/purinaeu/produto',
                'submenu' => [
                    ['label' => 'Todos os produtos', 'href' => '/purinaeu/produto'],
                    ['label' => 'Cães', 'href' => '/purinaeu/produto/pro-plan-adulto-frango'],
                    ['label' => 'Gatos', 'href' => '/purinaeu/produto/felix-sensacoes-gatos'],
                ],
            ],
            [
                'label' => 'Marca',
                'href' => '/purinaeu/marca',
            ],
            [
                'label' => 'Artigos',
                'href' => '/purinaeu/artigos',
                'submenu' => [
                    ['label' => 'Todos os artigos', 'href' => '/purinaeu/artigos'],
                    ['label' => 'Nutrição', 'href' => '/purinaeu/artigos?categoria=Nutrição'],
                    ['label' => 'Cuidados', 'href' => '/purinaeu/artigos?categoria=Cuidados'],
                ],
            ],
            [
                'label' => 'FAQ',
                'href' => '/purinaeu/faq',
            ],
        ];
    }

    protected function buildHeader(): TAG
    {
        // TAG::nav() encaminha os argumentos posicionais para um construtor
        // totalmente variadico; addClass() evita esse caminho com bug.
        $nav = TAG::nav()->addClass('main-nav')->setAria('label', 'Menu principal');
        $navList = TAG::ul('main-nav-list');

        foreach ($this->menuItems() as $item) {
            $hasSubmenu = ! empty($item['submenu']);
            $li = TAG::li($hasSubmenu ? 'has-submenu' : null);
            $li->append(TAG::a($item['href'], $item['label'], 'nav-link'));

            if ($hasSubmenu) {
                $submenu = TAG::ul('submenu');
                foreach ($item['submenu'] as $subItem) {
                    $submenu->append(
                        TAG::li(null)->append(TAG::a($subItem['href'], $subItem['label'], 'submenu-link'))
                    );
                }
                $li->append($submenu);
            }

            $navList->append($li);
        }

        $nav->append($navList);

        $logo = TAG::a('/purinaeu', null, 'logo');
        $logo->append(TAG::span('logo-mark', '🐾'));
        $logo->append(TAG::span('logo-text', 'Purina'));
        $logo->append(TAG::span('logo-accent', 'EU'));

        $searchForm = TAG::form('header-search', '/purinaeu/busca', 'get');
        $searchForm->append(TAG::input('header-search-input', null, 'q', null, 'search', placeholder: 'Buscar artigos e produtos'));
        $searchForm->append(TAG::button('header-search-button', null, null, 'Buscar', null, 'submit'));

        $headerInner = TAG::div('header-inner');
        $headerInner->append($logo);
        $headerInner->append($nav);
        $headerInner->append($searchForm);

        return TAG::header('site-header')->append($headerInner);
    }

    protected function buildFooter(): TAG
    {
        $columns = [
            [
                'title' => 'Purina EU',
                'links' => [
                    ['label' => 'Nossa marca', 'href' => '/purinaeu/marca'],
                    ['label' => 'Sustentabilidade', 'href' => '/purinaeu/marca#sustentabilidade'],
                    ['label' => 'Trabalhe conosco', 'href' => '#'],
                ],
            ],
            [
                'title' => 'Conteúdo',
                'links' => [
                    ['label' => 'Artigos', 'href' => '/purinaeu/artigos'],
                    ['label' => 'Produtos', 'href' => '/purinaeu/produto'],
                    ['label' => 'Perguntas frequentes', 'href' => '/purinaeu/faq'],
                ],
            ],
            [
                'title' => 'Ajuda',
                'links' => [
                    ['label' => 'Fale conosco', 'href' => '#'],
                    ['label' => 'Buscar', 'href' => '/purinaeu/busca'],
                    ['label' => 'Política de devolução', 'href' => '#'],
                ],
            ],
        ];

        $footerInner = TAG::div('footer-inner');

        foreach ($columns as $column) {
            $columnTag = TAG::div('footer-column');
            $columnTag->append(TAG::h4('footer-column-title', $column['title']));
            $list = TAG::ul('footer-column-list');
            foreach ($column['links'] as $link) {
                $list->append(TAG::li(null)->append(TAG::a($link['href'], $link['label'])));
            }
            $columnTag->append($list);
            $footerInner->append($columnTag);
        }

        $footerBottom = TAG::div('footer-bottom')
            ->append(TAG::small(null, '© '.date('Y').' Purina EU. Todos os direitos reservados. Site de demonstração.'));

        return TAG::footer('site-footer')->append($footerInner)->append($footerBottom);
    }

    protected function articleCard(Article $article): TAG
    {
        $card = TAG::div('card article-card');

        $card->append(
            TAG::a("/purinaeu/artigo/{$article['slug']}", null, 'article-card-media')
                ->append(TAG::img(null, null, null, src: $article['image'], alt: $article['title'])->allowContent(false))
        );

        $body = TAG::div('article-card-body');
        $body->append(TAG::span('tag', $article['category']));
        $body->append(TAG::h3('article-card-title')->append(TAG::a("/purinaeu/artigo/{$article['slug']}", $article['title'])));
        $body->append(TAG::p('article-card-excerpt', null, $article['excerpt']));
        $body->append(TAG::small('article-card-meta', "{$article['views']} visualizações · {$article->displayDate()}"));

        $card->append($body);

        return $card;
    }

    protected function productCard(Product $product): TAG
    {
        $card = TAG::div('card product-card');

        $card->append(
            TAG::a("/purinaeu/produto/{$product['slug']}", null, 'product-card-media')
                ->append(TAG::img(null, null, null, src: $product['image'], alt: $product['name'])->allowContent(false))
        );

        $body = TAG::div('product-card-body');
        $body->append(TAG::span('tag', $product['category']));
        $body->append(TAG::h3('product-card-title')->append(TAG::a("/purinaeu/produto/{$product['slug']}", $product['name'])));
        $body->append(TAG::span('product-card-price', $product['price']));

        $card->append($body);

        return $card;
    }
}
