<?php

namespace App\Templates\PurinaEU;

use App\Models\PageContent;
use CN\PHTML\ArrayTAG;
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
        $faq = PageContent::query()->where('page', 'faq')->firstOrFail();

        return $this->renderBlocks($faq->blocks);
    }
}
