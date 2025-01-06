<?php

use PHTML\FA\FA;
use PHTML\FOOTER;
use PHTML\TAG;

$footer = new FOOTER(append: TAG::div('bg-gray-800 text-white text-center mt-3 py-4', append: [
  TAG::p(append: [
    FA::iconCopyright('mr-2', alt: 'Copyright', title: 'Todos os direitos reservados a CautNew'),
    '2025 First Layout'
  ]),
]));

return $footer;
