<?php

namespace App\Templates\PurinaEU;

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
        $home = PageContent::query()->where('page', 'home')->firstOrFail();

        return $this->renderBlocks($home->blocks);
    }
}
