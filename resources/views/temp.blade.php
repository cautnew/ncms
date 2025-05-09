<?php

$body->append($slot ?? '');
$body->append($TAG::div('h3', html: 'vamos'));
$pageTitle->append('foi 2');
$pageTitle->append('foi 3');

echo $html;
