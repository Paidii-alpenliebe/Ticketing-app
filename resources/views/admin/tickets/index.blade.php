<x-layouts.admin>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Manajemen Tiket</h2>
            <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">+ Tambah Tiket</a>
        </div>

        <div class="overflow-x-auto bg-base-100 rounded-xl shadow-lg border border-gray-700">
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>No</th>
                        <th>Event</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-base-300">
                        <td>{{ $loop->iteration }}</td>
                        <td class="font-bold text-white">
                            {{-- Ganti title menjadi judul sesuai database Anda --}}
                            {{ $ticket->event->judul ?? 'Event N/A' }}
                        </td>
                        <td><div class="badge badge-secondary badge-outline">{{ $ticket->tipe }}</div></td>
                        <td class="text-success font-mono">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</td>
                        <td>{{ $ticket->stok }}</td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus tiket ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-error">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>