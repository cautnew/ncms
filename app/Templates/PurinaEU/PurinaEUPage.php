<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use App\Models\ContentBlock;
use App\Models\FaqCategory;
use App\Models\Product;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\DETAILS;
use CN\PHTML\Core\SUMMARY;
use CN\PHTML\Core\TAG;
use CN\PHTML\Templates\HTML5;
use Illuminate\Support\Collection;

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

    /**
     * Monta o conteudo de uma pagina/artigo/produto a partir de uma lista
     * ordenada de ContentBlock. Blocos "highlight" e "stat" consecutivos sao
     * agrupados em um unico wrapper (.info-blocks / .stats-bar) para manter o
     * layout em grade que o CSS ja espera.
     *
     * @param  iterable<ContentBlock>  $blocks
     */
    protected function renderBlocks(iterable $blocks, string $heroClass = 'hero'): ArrayTAG
    {
        $blocks = Collection::make($blocks)->values();
        $items = [];
        $count = $blocks->count();
        $i = 0;

        while ($i < $count) {
            $type = $blocks[$i]->type;

            if ($type === 'highlight' || $type === 'stat') {
                $group = [];
                while ($i < $count && $blocks[$i]->type === $type) {
                    $group[] = $blocks[$i]->data;
                    $i++;
                }
                $items[] = $type === 'highlight' ? $this->renderHighlightGroup($group) : $this->renderStatGroup($group);

                continue;
            }

            $items[] = $this->renderBlock($blocks[$i], $heroClass);
            $i++;
        }

        return new ArrayTAG($items);
    }

    private function renderBlock(ContentBlock $block, string $heroClass): TAG
    {
        return match ($block->type) {
            'hero' => $this->renderHeroBlock($block->data, $heroClass),
            'banner' => $this->renderBannerBlock($block->data),
            'image' => $this->renderImageBlock($block->data),
            'text' => $this->renderTextBlock($block->data),
            'testimonial' => $this->renderTestimonialBlock($block->data),
            'articles_grid' => $this->renderArticlesGridBlock($block->data),
            'faq_list' => $this->renderFaqListBlock(),
            default => TAG::div(null),
        };
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderHeroBlock(array $data, string $heroClass): TAG
    {
        $hero = TAG::div($heroClass);
        $hero->append(TAG::h1(null, $data['title']));
        $hero->append(TAG::p(null, null, $data['subtitle']));

        if (! empty($data['image'])) {
            $hero->append(TAG::img(null, null, null, src: $data['image'], alt: $data['title'])->allowContent(false));
        }

        $hero->append(TAG::a($data['button_href'], $data['button_label'], 'btn'));

        return $hero;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderBannerBlock(array $data): TAG
    {
        $banner = TAG::div('cta-banner');
        $banner->append(TAG::h2(null, $data['title']));
        $banner->append(TAG::p(null, null, $data['text']));
        $banner->append(TAG::a($data['button_href'], $data['button_label'], 'btn'));

        return $banner;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderImageBlock(array $data): TAG
    {
        $wrapper = TAG::div('article-image');
        $wrapper->append(TAG::img(null, null, null, src: $data['src'], alt: $data['alt'])->allowContent(false));

        if (! empty($data['caption'])) {
            $wrapper->append(TAG::span('article-image-caption', $data['caption']));
        }

        return $wrapper;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderTextBlock(array $data): TAG
    {
        $wrapper = TAG::div(null);

        if (! empty($data['heading'])) {
            $wrapper->append(TAG::h2(null, $data['heading']));
        }

        $paragraphs = preg_split('/\n\s*\n/', trim((string) $data['body'])) ?: [];
        foreach ($paragraphs as $paragraph) {
            if ($paragraph === '') {
                continue;
            }
            $wrapper->append(TAG::p(null, null, $paragraph));
        }

        return $wrapper;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderTestimonialBlock(array $data): TAG
    {
        $tag = TAG::div('testimonial');
        $tag->append('"'.$data['quote'].'"');
        $tag->append(TAG::span('testimonial-author', $data['author']));

        return $tag;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function renderHighlightGroup(array $items): TAG
    {
        $wrapper = TAG::div('info-blocks');

        foreach ($items as $item) {
            $block = TAG::div('info-block');
            $block->append(TAG::span('info-block-icon', $item['icon']));
            $block->append(TAG::h3(null, $item['title']));
            $block->append(TAG::p(null, null, $item['text']));
            $wrapper->append($block);
        }

        return $wrapper;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function renderStatGroup(array $items): TAG
    {
        $bar = TAG::div('stats-bar');

        foreach ($items as $item) {
            $entry = TAG::div(null);
            $entry->append(TAG::span('stat-number', $item['number']));
            $entry->append(TAG::span(null, $item['label']));
            $bar->append($entry);
        }

        return $bar;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderArticlesGridBlock(array $data): TAG
    {
        $section = TAG::section(null, 'artigos-mais-buscados');

        $title = TAG::div('section-title');
        $title->append(TAG::h2(null, $data['title'] ?? 'Artigos mais buscados'));
        $title->append(TAG::a('/purinaeu/artigos', 'Ver todos os artigos'));
        $section->append($title);

        $grid = TAG::div('grid grid-3');
        $limit = (int) ($data['limit'] ?? 6);
        foreach (Article::query()->orderByDesc('published_at')->limit($limit)->get() as $article) {
            $grid->append($this->articleCard($article));
        }
        $section->append($grid);

        return $section;
    }

    private function renderFaqListBlock(): TAG
    {
        $container = TAG::div(null);

        foreach (FaqCategory::query()->with('items')->orderBy('order')->get() as $category) {
            $categoryBlock = TAG::div('faq-category');
            $categoryBlock->append(TAG::h2(null, $category->name));

            foreach ($category->items as $item) {
                $categoryBlock->append(
                    (new DETAILS('accordion-item'))
                        ->append(new SUMMARY(null, null, $item->question))
                        ->append(TAG::p(null, null, $item->answer))
                );
            }

            $container->append($categoryBlock);
        }

        return $container;
    }
}
