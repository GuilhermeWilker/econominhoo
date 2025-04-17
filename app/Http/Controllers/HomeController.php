<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $rawTransactions = $this->rawTransactions();

        $transactionsByDay = [];

        foreach ($rawTransactions as $t) {
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

        return inertia('Index', [
            'transactionsByDay' => $transactionsByDay,
        ]);
    }

    public function dashboard()
    {
        $rawTransactions = $this->rawTransactions();

        $transactionsByDay = [];

        foreach ($rawTransactions as $t) {
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

        return inertia('Detailed', [
            'transactionsByDay' => $transactionsByDay,
        ]);
    }

    private function rawTransactions()
    {
        $userId = Auth::user()->id;

        $rawTransactions = DB::table('transactions')
            ->selectRaw(
                "CAST(strftime('%d', date) AS INTEGER) as day, type, SUM(amount) as total"
            )
            ->where('user_id', $userId)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->groupBy('day', 'type')
            ->get();

        return $rawTransactions;
    }
}
