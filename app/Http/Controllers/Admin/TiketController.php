<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Event;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    // Menampilkan daftar semua tiket
    public function index()
    {
        $tickets = Tiket::with('event')->latest()->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    // Form tambah tiket
    public function create()
    {
        $events = Event::all();
        return view('admin.tickets.create', compact('events'));
    }

    // Simpan tiket baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tipe' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Tiket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dibuat!');
    }

    // Form edit tiket
    // public function edit(Tiket $ticket)
    // {
    //     $events = Event::all();
    //     return view('admin.tickets.edit', compact('ticket', 'events'));
    // }

     public function edit($id)
    {
        $ticket = Tiket::findOrFail($id);
        $events = Event::all(); // Diperlukan untuk dropdown list event
        return view('admin.tickets.edit', compact('ticket', 'events'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Tiket::findOrFail($id);

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tipe' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Data tiket berhasil diperbarui.');
    }

    // Update tiket
    // public function update(Request $request, Tiket $ticket)
    // {
    //     $validated = $request->validate([
    //         'event_id' => 'required|exists:events,id',
    //         'tipe' => 'required|string|max:255',
    //         'harga' => 'required|numeric|min:0',
    //         'stok' => 'required|integer|min:0',
    //     ]);

    //     $ticket->update($validated);

    //     return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil diperbarui!');
    // }

    // Hapus tiket
    public function destroy(Tiket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Tiket berhasil dihapus!');
    }
}