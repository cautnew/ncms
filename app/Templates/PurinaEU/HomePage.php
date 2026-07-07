<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\TAG;

class HomePage extends PurinaEUPage
{
    protected function pageTitle(): string
    {
        return 'Home';
    }

    protected function metaDescription(): string
    {
        return 'Nutrição, cuidados e novidades para cães e gatos. Conheça os produtos e artigos da Purina EU.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        return new ArrayTAG([
            $this->buildHero(),
            $this->buildMostSearchedArticles(),
            $this->buildInfoBlocks(),
            $this->buildCta(),
        ]);
    }

    private function buildHero(): TAG
    {
        $hero = PageContent::section('home', 'hero', [
            'title' => '', 'subtitle' => '', 'button_label' => '', 'button_href' => '',
        ]);

        $tag = TAG::div('hero');
        $tag->append(TAG::h1(null, $hero['title']));
        $tag->append(TAG::p(null, null, $hero['subtitle']));
        $tag->append(TAG::a($hero['button_href'], $hero['button_label'], 'btn'));

        return $tag;
    }

    private function buildMostSearchedArticles(): TAG
    {
        $section = TAG::section(null, 'artigos-mais-buscados');

        $title = TAG::div('section-title');
        $title->append(TAG::h2(null, 'Artigos mais buscados'));
        $title->append(TAG::a('/purinaeu/artigos', 'Ver todos os artigos'));
        $section->append($title);

        $grid = TAG::div('grid grid-3');
        foreach (Article::query()->orderByDesc('published_at')->limit(6)->get() as $article) {
            $grid->append($this->articleCard($article));
        }
        $section->append($grid);

        return $section;
    }

    private function buildInfoBlocks(): TAG
    {
        $wrapper = TAG::div('info-blocks');

        foreach (PageContent::section('home', 'info_blocks') as $block) {
            $item = TAG::div('info-block');
            $item->append(TAG::span('info-block-icon', $block['icon']));
            $item->append(TAG::h3(null, $block['title']));
            $item->append(TAG::p(null, null, $block['text']));
            $wrapper->append($item);
        }

        return $wrapper;
    }

    private function buildCta(): TAG
    {
        $cta = PageContent::section('home', 'cta', [
            'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
        ]);

        $tag = TAG::div('cta-banner');
        $tag->append(TAG::h2(null, $cta['title']));
        $tag->append(TAG::p(null, null, $cta['text']));
        $tag->append(TAG::a($cta['button_href'], $cta['button_label'], 'btn'));

        return $tag;
    }
}
