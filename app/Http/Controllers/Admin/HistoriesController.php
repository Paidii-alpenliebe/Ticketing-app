<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    /**
     * Menampilkan daftar riwayat pemesanan untuk Admin.
     */
    public function index()
    {
        $histories = Order::with(['user', 'event', 'paymentType'])
            ->latest()
            ->get();

        return view('admin.histories.index', compact('histories'));
    }

    /**
     * Menampilkan detail rincian satu pesanan tertentu.
     */
    public function show(string $history)
    {
        $order = Order::with([
            'user', 
            'event', 
            'paymentType', 
            'detailOrders.tiket'
        ])->findOrFail($history);

        return view('admin.histories.show', compact('order'));
    }
}