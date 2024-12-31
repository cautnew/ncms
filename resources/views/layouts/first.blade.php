<?php

use PHTML\HTML;

$layout = new HTML();
$layout->setLang(str_replace('_', '-', app()->getLocale()));
$layout->append($head = require_once(resource_path('/views/layouts/first/first.head.blade.php')));
$layout->append($body = require_once(resource_path('/views/layouts/first/first.body.blade.php')));

echo $layout;
