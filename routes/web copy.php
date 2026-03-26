<?php

use App\Http\Controllers\Tests\MeuCurriculo;
use App\Http\Controllers\Tests\MyHome;
use App\Models\Users\Gender;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Tests\Test02;
use App\Http\Controllers\Tests\ChegadaTaise;

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
Route::get('/my-home-blog', function () { return view('myhomeblog.my-home'); });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/taxonomy', function () {
        return Inertia::render('taxonomy/all');
    })->name('taxonomy');

    Route::get('/taxonomy/gender', function () {
        return Inertia::render('taxonomy/gender/page', ['gender_list' => Gender::all(['id', 'name', 'symbol'])]);
    })->name('taxonomy.gender');

    Route::get('/taxonomy/gender/create', function () {
        return Inertia::render('taxonomy/gender/create');
    })->name('taxonomy.gender.create');

    Route::get('/media', function () {
        return Inertia::render('media/index');
    })->name('media');
});

Route::get('/session-test', function () {
    session(['test' => 'it works!']);

    return session('test');
});

require __DIR__ . '/pages.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/website.php';
