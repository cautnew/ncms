<?php

use App\Templates\PurinaEU\ArticleListPage;
use App\Templates\PurinaEU\ArticlePage;
use App\Templates\PurinaEU\BrandLandingPage;
use App\Templates\PurinaEU\FaqPage;
use App\Templates\PurinaEU\HomePage;
use App\Templates\PurinaEU\ProductListPage;
use App\Templates\PurinaEU\ProductPage;
use App\Templates\PurinaEU\SearchResultsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/new-home', function () {
    $template = new HomePage;

    return $template->render();
})->name('new-home');

Route::get('/templates/purinaeu/assets/css/geral.css', function () {
    return response()->file(app_path('Templates/PurinaEU/assets/css/geral.css'), ['Content-Type' => 'text/css']);
});

Route::get('/templates/purinaeu/assets/js/topmenu.js', function () {
    return response()->file(app_path('Templates/PurinaEU/assets/js/topmenu.js'), ['Content-Type' => 'application/javascript']);
});

Route::prefix('purinaeu')->name('purinaeu.')->group(function () {
    Route::get('/', function () {
        return (new HomePage)->render();
    })->name('home');

    Route::get('/produtos', function () {
        return (new ProductListPage())->render();
    })->name('product.list');

    Route::get('/produto/{slug?}', function (?string $slug = null) {
        return (new ProductPage($slug))->render();
    })->name('product');

    Route::get('/marca', function () {
        return (new BrandLandingPage)->render();
    })->name('brand');

    Route::get('/artigo/{slug?}', function (?string $slug = null) {
        return (new ArticlePage($slug))->render();
    })->name('article');

    Route::get('/artigos', function (Request $request) {
        return (new ArticleListPage($request->query('categoria')))->render();
    })->name('article.list');

    Route::get('/faq', function () {
        return (new FaqPage)->render();
    })->name('faq');

    Route::get('/busca', function (Request $request) {
        return (new SearchResultsPage((string) $request->query('q', '')))->render();
    })->name('search');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
