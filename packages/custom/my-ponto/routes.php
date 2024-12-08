<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/ponto', function () {
  return Inertia::render('Ponto/Ponto');
})->middleware(['auth', 'verified'])->name('ncms.ponto');

Route::get('/ncms/pronto', function () {
  return Inertia::render('DefaultAdminPage', [
    'title' => 'Pronto',
    'componentImport' => [
      [
        'name' => 'BodySimpleCard',
        'from' => '../Components/BodySimpleCard.vue'
      ],
    ],
    'content' => [
      [
        'componentName' => 'BodySimpleCard',
        'content' => 'Pronto para começar a trabalhar de novo?'
      ]
    ]
  ]);
})->middleware(['auth', 'verified'])->name('ncms.pronto');
