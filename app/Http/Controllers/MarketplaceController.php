<?php

namespace App\Http\Controllers;

use App\Models\Tryout;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketplaceController extends Controller
{
    public function index()
    {
        return Inertia::render('Marketplace/Index', [
            'tryouts' => Tryout::latest()->get()
        ]);
    }

    public function buy(Tryout $tryout)
    {
        // Simulasi transaksi manual
        Transaction::create([
            'user_id' => auth()->id(),
            'tryout_id' => $tryout->id,
            'amount' => $tryout->price,
            'status' => 'pending' // Admin akan Approve di menu Transaksi
        ]);

        return redirect()->route('dashboard')->with('message', 'Pesanan dibuat! Silakan hubungi admin untuk aktivasi.');
    }
}