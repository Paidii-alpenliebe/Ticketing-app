<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $paymentTypes = PaymentType::all();
        return view('admin.payment_types.index', compact('paymentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tipe_pembayaran' => 'required|string|max:255'
        ]);

        PaymentType::create($request->all());

        return redirect()->back()->with('success', 'Tipe pembayaran berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tipe_pembayaran' => 'required|string|max:255'
        ]);

        $paymentType = PaymentType::findOrFail($id);
        $paymentType->update($request->all());

        return redirect()->back()->with('success', 'Tipe pembayaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->delete();

        return redirect()->back()->with('success', 'Tipe pembayaran berhasil dihapus!');
    }
}