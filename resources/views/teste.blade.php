<?php

use PHTML\Core\P;
use PHTML\Templates\HTML5;

$page = new HTML5();
$page->setPageTitle($pageTitle ?? 'Testando o título');
$page->appendToBody(new P(html: 'Caraca'));

echo $page;
