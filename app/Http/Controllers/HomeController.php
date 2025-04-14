<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $transactionsByDay = [
            2 => ['entrada' => 1133, 'gasto' => 95],
            15 => ['entrada' => 3, 'gasto' => 12],
            17 => ['entrada' => 12, 'gasto' => 557],
            8 => ['entrada' => 0, 'gasto' => 557],
            27 => ['entrada' => 24, 'gasto' => 59],
            30 => ['entrada' => 1340, 'gasto' => 234],
        ];

        return inertia('Index', [
            'transactionsByDay' => $transactionsByDay,
        ]);
    }
}
