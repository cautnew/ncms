<?php

use App\Http\Controllers\NCMS\CommandController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(callback: function () {
  Route::redirect('/ncms/commands', '/ncms/command');
  Route::get('/ncms/command', [CommandController::class, 'index'])->name('ncms.command');
});
