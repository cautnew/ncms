<?php

use PHTML\FOOTER;
use PHTML\TAG;

$footer = new FOOTER(append: TAG::div('bg-gray-800 text-white text-center mt-3 py-4', append: [
  TAG::p(html: '© 2024 First Layout'),
]));

return $footer;
