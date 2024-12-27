<?php

use PHTML\HTML;
use PHTML\BODY;
use PHTML\HEAD;
use PHTML\TAG;
use PHTML\P;

$layout = new HTML();
$layout->setLang(str_replace('_', '-', app()->getLocale()));
$layout->append($head = new HEAD());
$head->append(TAG::tagTitle(($pageTitle ?? 'First Layout') . " - NCMS"));

$layout->append($body = new BODY());

$body->addClass('font-sans antialiased');
$body->append(new P(html: $slot));

echo $layout;
