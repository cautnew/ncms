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
        $brand = PageContent::query()->where('page', 'brand')->firstOrFail();

        return $this->renderBlocks($brand->blocks, heroClass: 'brand-hero');
    }
}
