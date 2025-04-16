<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense,investment',
            'date' => 'required|date',
        ]);

        if ($validated) {
            Transaction::create($validated);

            return back()->with('success', 'Transaction created successfully');
        }

        return back()->with('error', 'Failed to create transaction');
    }
}
