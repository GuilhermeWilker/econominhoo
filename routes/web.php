<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Models\InvestmentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test', function () {
    $symbols = DB::table('transactions')
        ->where('type', 'investment')
        ->selectRaw('DISTINCT description as symbol')
        ->pluck('symbol');

    $symbols = $symbols->all();

    foreach ($symbols as $symbol) {
        $res = Http::get("https://brapi.dev/api/quote/$symbol?token=" . env('BRAPI_TOKEN'));

        $data = $res->json();

        foreach ($data['results'] as $stock) {
            InvestmentData::updateOrInsert([
                'symbol' => $stock['symbol'],
                'current_value' => $stock['regularMarketPrice'],
                'market_variation' => $stock['regularMarketChange'],
                'market_variation_pct' => $stock['regularMarketChangePercent'],
                'logo_url' => $stock['logourl'],
            ]);
        }
    }

    return 'Inserted or updated successfully';
})->name('test');

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
