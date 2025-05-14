<?php

$tempa = view('temp_01', ['valorNovo' => 'Tá indo de novo']);

$body->append($slot ?? '');
$body->append($TAG::div('h2', html: 'vamos'));
$body->append($TAG::div('h3', html: $tempa));
$pageTitle->append('foi 2');
$pageTitle->append('foi 3');

echo $html;
