<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Transactions/Index', [
            'transactions' => Transaction::with(['user', 'tryout'])->latest()->get()
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Logika sederhana untuk menyetujui pembayaran secara manual
        $transaction->update(['status' => $request->status]);
        return back()->with('message', 'Status transaksi diperbarui!');
    }
}