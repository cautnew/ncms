<?php
$numberRandom = rand(1, 7);
?>
<img src="{{ asset('img/logo/LOGO_'.$numberRandom.'.png') }}" {{ $attributes }} alt="Logo">
