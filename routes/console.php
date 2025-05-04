<?php


use App\Models\InvestmentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
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
})
    ->timezone('America/Sao_Paulo')
    ->onSuccess(function () {
        Log::info('Investment data updated successfully');
    })
    ->onFailure(function () {
        Log::error('Failed to update investment data');
    })
    ->twiceDailyAt('10:00', '14:00');
