<?php

namespace App\Templates\PurinaEU;

use App\Models\FaqCategory;
use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\DETAILS;
use CN\PHTML\Core\SUMMARY;
use CN\PHTML\Core\TAG;

class FaqPage extends PurinaEUPage
{
    protected function pageTitle(): string
    {
        return 'Perguntas Frequentes';
    }

    protected function metaDescription(): string
    {
        return 'Tire suas dúvidas sobre produtos, pedidos, entregas e nutrição de cães e gatos.';
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        $intro = PageContent::section('faq', 'intro', ['title' => '', 'text' => '']);

        return new ArrayTAG([
            TAG::h1(null, $intro['title']),
            TAG::p(null, null, $intro['text']),
            $this->buildFaqCategories(),
            $this->buildContactCta(),
        ]);
    }

    private function buildFaqCategories(): TAG
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

    private function buildContactCta(): TAG
    {
        $cta = PageContent::section('faq', 'cta', [
            'title' => '', 'text' => '', 'button_label' => '', 'button_href' => '',
        ]);

        $tag = TAG::div('cta-banner');
        $tag->append(TAG::h2(null, $cta['title']));
        $tag->append(TAG::p(null, null, $cta['text']));
        $tag->append(TAG::a($cta['button_href'], $cta['button_label'], 'btn'));

        return $tag;
    }
}
