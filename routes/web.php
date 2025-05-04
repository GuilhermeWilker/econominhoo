<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Models\InvestmentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/detailed', [HomeController::class, 'dashboard'])
        ->name('detailed');

    Route::post('/transaction', [TransactionController::class, 'store'])
        ->name('transaction.tore');
});

Route::get('dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
