<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'top');

// Public: an entrant registers, or re-fetches the QR ticket for their secret.
Route::post('/entry', [EntryController::class, 'setentry']);
Route::get('/entry', [EntryController::class, 'getentry']);

// Staff only. The views used to guard themselves with a raw
// header('Location: /login') call while the JSON endpoints behind them stayed
// open; the middleware covers both verbs now.
Route::middleware('auth')->group(function () {
    Route::post('/status', [EntryController::class, 'upstatus']);
    Route::get('/status', [EntryController::class, 'getstatus']);
    Route::post('/list', [EntryController::class, 'liststatus']);
    Route::get('/list', [EntryController::class, 'list']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
