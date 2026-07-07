<?php

namespace App\Templates\PurinaEU;

use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\TAG;

class BrandLandingPage extends PurinaEUPage
{
    protected function pageTitle(): string
    {
        return 'Nossa Marca';
    }

    protected function metaDescription(): string
    {
        return 'Conheça a história, os valores e o compromisso da Purina com a nutrição e o bem-estar de cães e gatos.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        return new ArrayTAG([
            $this->buildHero(),
            $this->buildStory(),
            $this->buildStats(),
            $this->buildValues(),
            $this->buildTestimonial(),
            $this->buildCta(),
        ]);
    }

    private function buildHero(): TAG
    {
        $hero = PageContent::section('brand', 'hero', [
            'title' => '', 'subtitle' => '', 'button_label' => '', 'button_href' => '',
        ]);

        $tag = TAG::div('brand-hero');
        $tag->append(TAG::h1(null, $hero['title']));
        $tag->append(TAG::p(null, null, $hero['subtitle']));
        $tag->append(TAG::a($hero['button_href'], $hero['button_label'], 'btn'));

        return $tag;
    }

    private function buildStory(): TAG
    {
        $history = PageContent::section('brand', 'story_history', [
            'title' => '', 'text1' => '', 'text2' => '', 'image' => '',
        ]);
        $sustainability = PageContent::section('brand', 'story_sustainability', [
            'title' => '', 'text' => '', 'image' => '',
        ]);

        $block1 = TAG::div('brand-story', 'nossa-historia');
        $block1->append(
            TAG::div('brand-story-image')
                ->append(TAG::img(null, null, null, src: $history['image'], alt: 'Equipe de pesquisa Purina')->allowContent(false))
        );
        $block1->append(
            TAG::div('brand-story-text')
                ->append(TAG::h2(null, $history['title']))
                ->append(TAG::p(null, null, $history['text1']))
                ->append(TAG::p(null, null, $history['text2']))
        );

        $block2 = TAG::div('brand-story reverse', 'sustentabilidade');
        $block2->append(
            TAG::div('brand-story-image')
                ->append(TAG::img(null, null, null, src: $sustainability['image'], alt: 'Ingredientes sustentáveis')->allowContent(false))
        );
        $block2->append(
            TAG::div('brand-story-text')
                ->append(TAG::h2(null, $sustainability['title']))
                ->append(TAG::p(null, null, $sustainability['text']))
        );

        return TAG::div(null)->append($block1)->append($block2);
    }

    private function buildStats(): TAG
    {
        $bar = TAG::div('stats-bar');
        foreach (PageContent::section('brand', 'stats') as $stat) {
            $item = TAG::div(null);
            $item->append(TAG::span('stat-number', $stat['number']));
            $item->append(TAG::span(null, $stat['label']));
            $bar->append($item);
        }

        return $bar;
    }

    private function buildValues(): TAG
    {
        $wrapper = TAG::div('info-blocks');
        foreach (PageContent::section('brand', 'values') as $value) {
            $item = TAG::div('info-block');
            $item->append(TAG::span('info-block-icon', $value['icon']));
            $item->append(TAG::h3(null, $value['title']));
            $item->append(TAG::p(null, null, $value['text']));
            $wrapper->append($item);
        }

        return $wrapper;
    }

    private function buildTestimonial(): TAG
    {
        $testimonial = PageContent::section('brand', 'testimonial', ['quote' => '', 'author' => '']);

        $tag = TAG::div('testimonial');
        $tag->append('"'.$testimonial['quote'].'"');
        $tag->append(TAG::span('testimonial-author', $testimonial['author']));

        return $tag;
    }

    private function buildCta(): TAG
    {
        $cta = PageContent::section('brand', 'cta', [
            'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
        ]);

        $tag = TAG::div('cta-banner');
        $tag->append(TAG::h2(null, $cta['title']));
        $tag->append(TAG::p(null, null, $cta['text']));
        $tag->append(TAG::a($cta['button_href'], $cta['button_label'], 'btn'));

        return $tag;
    }
}
