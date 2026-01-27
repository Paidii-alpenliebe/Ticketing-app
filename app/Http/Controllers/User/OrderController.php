<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user() ?? \App\Models\User::first();
        // Memuat relasi paymentType agar muncul di tabel index user
        $orders = Order::where('user_id', $user->id)
            ->with(['event', 'paymentType']) 
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Memuat relasi paymentType untuk detail pesanan
        $order->load('detailOrders.tiket', 'event', 'paymentType');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        // 1. Tambahkan payment_type_id ke dalam validasi
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'payment_type_id' => 'required|exists:payment_types,id', // Validasi baru
            'items' => 'required|array|min:1',
            'items.*.tiket_id' => 'required|integer|exists:tikets,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($data, $user) {
                $total = 0;
                
                foreach ($data['items'] as $it) {
                    $t = Tiket::lockForUpdate()->findOrFail($it['tiket_id']);
                    if ($t->stok < $it['jumlah']) {
                        throw new \Exception("Stok tidak cukup untuk tipe: {$t->tipe}");
                    }
                    $total += ($t->harga ?? 0) * $it['jumlah'];
                }

                // 2. Masukkan payment_type_id ke dalam pembuatan Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $data['event_id'],
                    'payment_type_id' => $data['payment_type_id'], // Simpan ID pembayaran
                    'order_date' => Carbon::now(),
                    'total_harga' => $total,
                ]);

                foreach ($data['items'] as $it) {
                    $t = Tiket::findOrFail($it['tiket_id']);
                    $subtotal = ($t->harga ?? 0) * $it['jumlah'];
                    DetailOrder::create([
                        'order_id' => $order->id,
                        'tiket_id' => $t->id,
                        'jumlah' => $it['jumlah'],
                        'subtotal_harga' => $subtotal,
                    ]);

                    $t->stok = max(0, $t->stok - $it['jumlah']);
                    $t->save();
                }

                return $order;
            });

            session()->flash('success', 'Pesanan berhasil dibuat.');

            return response()->json([
                'ok' => true, 
                'order_id' => $order->id, 
                'redirect' => route('orders.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }
    }
}