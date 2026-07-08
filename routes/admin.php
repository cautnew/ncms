<?php

use App\Http\Controllers\Admin\ArticleBlockController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqItemController;
use App\Http\Controllers\Admin\PageBlockController;
use App\Http\Controllers\Admin\PageIndexController;
use App\Http\Controllers\Admin\ProductBlockController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * Registra as rotas de CRUD + reordenacao de ContentBlock (subir/descer)
 * para um "dono" polimorfico (pagina, artigo ou produto).
 */
$registerBlockRoutes = function (string $prefix, string $param, string $name, string $controller) {
    Route::prefix("{$prefix}/{{$param}}/blocos")->name("{$name}.blocks.")->group(function () use ($controller) {
        Route::get('/', [$controller, 'index'])->name('index');
        Route::get('novo', [$controller, 'create'])->name('create');
        Route::post('/', [$controller, 'store'])->name('store');
        Route::get('{block}/editar', [$controller, 'edit'])->name('edit');
        Route::put('{block}', [$controller, 'update'])->name('update');
        Route::delete('{block}', [$controller, 'destroy'])->name('destroy');
        Route::post('{block}/subir', [$controller, 'moveUp'])->name('moveUp');
        Route::post('{block}/descer', [$controller, 'moveDown'])->name('moveDown');
    });
};

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () use ($registerBlockRoutes) {
    Route::redirect('/', '/admin/artigos');

    Route::resource('artigos', ArticleController::class)->parameters(['artigos' => 'article'])->names('articles');
    Route::resource('produtos', ProductController::class)->parameters(['produtos' => 'product'])->names('products');
    Route::resource('faq/categorias', FaqCategoryController::class)->parameters(['categorias' => 'faqCategory'])->names('faq-categories');
    Route::resource('faq/perguntas', FaqItemController::class)->parameters(['perguntas' => 'faqItem'])->names('faq-items');

    $registerBlockRoutes('artigos', 'article', 'articles', ArticleBlockController::class);
    $registerBlockRoutes('produtos', 'product', 'products', ProductBlockController::class);
    $registerBlockRoutes('paginas', 'pageContent', 'pages', PageBlockController::class);

    Route::get('paginas', [PageIndexController::class, 'index'])->name('pages.index');
});
