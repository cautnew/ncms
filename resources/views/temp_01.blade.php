<?php

use PHTML\TAG;

$div = TAG::div();

$div->append(TAG::span(html: 'E agora???'));

echo $div;

?>

<strong>{{ $valorNovo }}</strong>