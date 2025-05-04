<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\InvestmentData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $rawTransactions = $this->rawTransactions($userId);
        $transactionsByDay = $this->groupTransactionsByDay($rawTransactions);

        $transactions = Transaction::where('user_id', $userId)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return inertia('Index', [
            'transactionsByDay' => $transactionsByDay,
            'transactions' => $transactions,
        ]);
    }

    public function dashboard()
    {
        $userId = Auth::id();

        $stocks = Auth::user()->transactions()
            ->where('type', 'investment')
            ->whereNotNull('symbol')
            ->get();

        $portfolio = InvestmentData::whereIn('symbol', $stocks->pluck('description'))
            ->get();

        $rawTransactions = $this->rawTransactions($userId);
        $transactionsByDay = $this->groupTransactionsByDay($rawTransactions);

        $transactions = Transaction::where('user_id', $userId)
            ->latest()
            ->paginate(5)
            ->withQueryString();


        return inertia('Detailed', [
            'transactionsByDay' => $transactionsByDay,
            'transactions' => $transactions,
            'portfolio' => $portfolio,
        ]);
    }

    private function groupTransactionsByDay(Collection $rawCollection): array
    {
        $transactionsByDay = [];

        foreach ($rawCollection as $t) {
            $day = (int) $t->day;

            $typeMap = [
                'income' => 'entrada',
                'expense' => 'gasto',
                'investment' => 'investimento',
            ];

            $type = $typeMap[$t->type] ?? $t->type;

            if (!isset($transactionsByDay[$day])) {
                $transactionsByDay[$day] = [
                    'entrada' => 0,
                    'gasto' => 0,
                    'investimento' => 0,
                ];
            }

            $transactionsByDay[$day][$type] = (int) $t->total;
        }

        return $transactionsByDay;
    }

    private function rawTransactions(int $userId): Collection
    {
        return DB::table('transactions')
            ->selectRaw("CAST(strftime('%d', date) AS INTEGER) as day, type, SUM(amount) as total")
            ->where('user_id', $userId)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->groupBy('day', 'type')
            ->get();
    }
}
