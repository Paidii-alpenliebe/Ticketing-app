@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-white">Manajemen Tipe Pembayaran</h2>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
            + Tambah Tipe
        </button>
    </div>

    <div class="bg-zinc-900 rounded-lg overflow-hidden border border-zinc-800">
        <table class="w-full text-left text-zinc-400">
            <thead class="bg-zinc-800 text-zinc-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Tipe Pembayaran</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($paymentTypes as $key => $type)
                <tr>
                    <td class="px-6 py-4">{{ $key + 1 }}</td>
                    <td class="px-6 py-4 text-white font-medium">{{ $type->nama_tipe_pembayaran }}</td>
                    <td class="px-6 py-4 flex gap-2">
                        <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">Edit</button>
                        <form action="{{ route('admin.payment-types.destroy', $type->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modal-tambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-zinc-900 p-6 rounded-lg w-96 border border-zinc-700">
        <h3 class="text-white mb-4">Tambah Tipe Pembayaran</h3>
        <form action="{{ route('admin.payment-types.store') }}" method="POST">
            @csrf
            <input type="text" name="nama_tipe_pembayaran" class="w-full bg-zinc-800 border-zinc-700 text-white rounded mb-4" placeholder="Contoh: Transfer Bank" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-zinc-400">Batal</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection