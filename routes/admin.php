<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqItemController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/artigos');

    Route::resource('artigos', ArticleController::class)->parameters(['artigos' => 'article'])->names('articles');
    Route::resource('produtos', ProductController::class)->parameters(['produtos' => 'product'])->names('products');
    Route::resource('faq/categorias', FaqCategoryController::class)->parameters(['categorias' => 'faqCategory'])->names('faq-categories');
    Route::resource('faq/perguntas', FaqItemController::class)->parameters(['perguntas' => 'faqItem'])->names('faq-items');

    Route::prefix('paginas')->name('pages.')->group(function () {
        Route::get('home', [PageContentController::class, 'home'])->name('home');
        Route::put('home', [PageContentController::class, 'updateHome'])->name('home.update');

        Route::get('marca', [PageContentController::class, 'brand'])->name('brand');
        Route::put('marca', [PageContentController::class, 'updateBrand'])->name('brand.update');

        Route::get('faq', [PageContentController::class, 'faq'])->name('faq');
        Route::put('faq', [PageContentController::class, 'updateFaq'])->name('faq.update');

        Route::get('artigos', [PageContentController::class, 'articles'])->name('articles');
        Route::put('artigos', [PageContentController::class, 'updateArticles'])->name('articles.update');
    });
});
