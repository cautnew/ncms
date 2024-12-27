<?php

use PHTML\UL;
use PHTML\TAG;

$ul = new UL('font-sans antialiased');
$ul->append([
  TAG::li(html: 'Item 1'),
  TAG::li(html: 'Item 2'),
  TAG::li(html: 'Item 3')
]);

?>

<x-first-layout>
  <x-slot name="pageTitle">Teste</x-slot>
  <?= $ul ?>
</x-first-layout>