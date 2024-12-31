<?php

use PHTML\ARTICLE;
use PHTML\BODY;
use PHTML\HEADER;
use PHTML\MAIN;
use PHTML\DIV;
use PHTML\P;
use PHTML\TAG;

$body = new BODY(class: 'font-sans antialiased', append: [
  $fBody = new DIV('min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-white')
]);

$fBody->append($header = new HEADER(append: [
  new DIV('bg-gray-800 text-white text-center py-4', append: [
    new P(html: 'Opa')
  ])
]));
$fBody->append($main = new MAIN(html: $slot));
$fBody->append($article = new ARTICLE(html: ''));
$fBody->append($footer = require_once(resource_path('/views/layouts/first/first.footer.blade.php')));

return $body;
