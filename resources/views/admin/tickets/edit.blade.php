<x-layouts.admin>
    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-ghost btn-sm">← Kembali ke Daftar</a>
        </div>

        <div class="card bg-base-100 shadow-xl border border-gray-700">
            <div class="card-body">
                <h2 class="card-title text-2xl font-bold mb-6 text-white">Edit Tiket</h2>

                <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-control mb-4">
                        <label class="label font-semibold text-gray-300">Pilih Event</label>
                        <select name="event_id" class="select select-bordered w-full @error('event_id') select-error @enderror" required>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ $ticket->event_id == $event->id ? 'selected' : '' }}>
                                    {{ $event->judul }} {{-- Menggunakan kolom 'judul' sesuai database --}}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control mb-4">
                        <label class="label font-semibold text-gray-300">Tipe Tiket</label>
                        <select name="tipe" class="select select-bordered w-full @error('tipe') select-error @enderror" required>
                            <option value="Tiket Reguler" {{ $ticket->tipe == 'Tiket Reguler' ? 'selected' : '' }}>Tiket Reguler</option>
                            <option value="VIP" {{ $ticket->tipe == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="Early Bird" {{ $ticket->tipe == 'Early Bird' ? 'selected' : '' }}>Early Bird</option>
                        </select>
                        @error('tipe') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-semibold text-gray-300">Harga (Rp)</label>
                            <div class="join w-full">
                                <span class="btn join-item no-animation">Rp</span>
                                <input type="number" name="harga" step="0.01" value="{{ (int)$ticket->harga }}" class="input input-bordered join-item w-full @error('harga') input-error @enderror" required>
                            </div>
                            @error('harga') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control">
                            <label class="label font-semibold text-gray-300">Stok</label>
                            <input type="number" name="stok" value="{{ $ticket->stok }}" class="input input-bordered @error('stok') input-error @enderror" required>
                            @error('stok') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-8">
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary px-10">Perbarui Tiket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>