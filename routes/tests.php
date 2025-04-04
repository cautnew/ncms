<?php

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/testesidebar', function () {
    $userData = User::find(Auth::id())->first();
    return Inertia::render('TesteSidebar', [
        'name' => $userData->name,
        'userData' => $userData
    ]);
})->middleware(['auth', 'verified'])->name('ncms.teste-sidebar');
