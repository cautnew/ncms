<?php

use App\Http\Controllers\Tests\MeuCurriculo;
use App\Http\Controllers\Tests\MyHome;
use App\Models\Users\Gender;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Tests\Test02;
use App\Http\Controllers\Tests\ChegadaTaise;
use App\Http\Controllers\Taxonomy\TaxonomyWebController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/teste1', function () {
    return view('teste', ['pageTitle' => 'Olha isso!']);
})->name('teste1');

Route::get('/teste2', [Test02::class, 'index']);
Route::get('/chegada-taise', [ChegadaTaise::class, 'index']);
Route::get('/meu-curriculo', [MeuCurriculo::class, 'index']);

Route::get('/my-home', [MyHome::class, 'index']);
Route::get('/my-home-blog', function () {
    return view('myhomeblog.my-home');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // ── Taxonomy Web Routes ──────────────────────────────────────
    Route::prefix('taxonomy')->name('taxonomy.')->group(function () {
        Route::get('/', [TaxonomyWebController::class, 'index'])->name('index');
        Route::get('/create', [TaxonomyWebController::class, 'create'])->name('create');
        Route::get('/{slug}', [TaxonomyWebController::class, 'show'])->name('show');
        Route::get('/{slug}/edit', [TaxonomyWebController::class, 'edit'])->name('edit');
        Route::get('/{slug}/delete', [TaxonomyWebController::class, 'confirmDelete'])->name('delete');
        Route::get('/{slug}/history', [TaxonomyWebController::class, 'taxonomyHistory'])->name('history');

        // Terms
        Route::get('/{slug}/terms/create', [TaxonomyWebController::class, 'createTerm'])->name('terms.create');
        Route::get('/{slug}/terms/{termSlug}/edit', [TaxonomyWebController::class, 'editTerm'])->name('terms.edit');
        Route::get('/{slug}/terms/{termSlug}/delete', [TaxonomyWebController::class, 'confirmDeleteTerm'])->name('terms.delete');
        Route::get('/{slug}/terms/{termSlug}/history', [TaxonomyWebController::class, 'termHistory'])->name('terms.history');
    });

    Route::get('/media', function () {
        return Inertia::render('media/index');
    })->name('media');
});

Route::get('/session-test', function () {
    session(['test' => 'it works!']);

    return session('test');
});

for ($i = 1; $i <= 10; $i++) {
    Route::get('/teste-' . $i, function () use ($i) {
        return view('teste', ['pageTitle' => 'Olha isso!']);
    })->name('teste_' . $i);
}

require __DIR__ . '/pages.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/website.php';
