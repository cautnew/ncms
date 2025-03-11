<?php

use App\Models\NCMS\People\User;
use App\Http\Controllers\NCMS\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NCMS\NCMSAuthMiddleware;
use App\Models\NCMS\People\Person;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([NCMSAuthMiddleware::class])->prefix('ncms')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])
    ->name('ncms.auth.login')
    ->withoutMiddleware(NCMSAuthMiddleware::class);

    Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('ncms.auth.authenticate')
    ->withoutMiddleware(NCMSAuthMiddleware::class);

    Route::post('/logout', function () {
        Auth::logout();
        session()->flush();
        return redirect()->route('ncms.auth.login');
    })->name('ncms.auth.logout');

    Route::get('/register', [AuthController::class, 'create'])
    ->name('ncms.auth.create')
    ->withoutMiddleware(NCMSAuthMiddleware::class);

    Route::post('/register', [AuthController::class, 'store'])
    ->name('ncms.auth.store')
    ->withoutMiddleware(NCMSAuthMiddleware::class);

    Route::get('/home',  function () {
        $id = session('user-id');
        $person = Person::findByUserId($id)->first();
        return view('ncms.pages.home')->with('name', $person->name);
    })
    ->name('ncms.home');

    Route::get('/', function () {
        return redirect()->route('ncms.home');
    });
});
