<x-layouts.admin>
    <div class="p-6 max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-xl border border-gray-700">
            <div class="card-body">
                <h2 class="card-title text-2xl font-bold mb-6">Buat Tiket Baru</h2>
                <form action="{{ route('admin.tickets.store') }}" method="POST">
                    @csrf
                    <div class="form-control mb-4">
                        <label class="label font-semibold text-gray-300">Pilih Event</label>
                        <select name="event_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>-- Pilih Event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control mb-4">
                        <label class="label font-semibold text-gray-300">Tipe Tiket</label>
                        <select name="tipe" class="select select-bordered w-full" required>
                            <option value="" disabled selected>-- Pilih Tipe --</option>
                            <option value="Tiket Reguler">Tiket Reguler</option>
                            <option value="VIP">VIP</option>
                            <option value="Early Bird">Early Bird</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label font-semibold text-gray-300">Harga (Rp)</label>
                            <input type="number" name="harga" step="0.01" class="input input-bordered" required>
                        </div>
                        <div class="form-control">
                            <label class="label font-semibold text-gray-300">Stok</label>
                            <input type="number" name="stok" class="input input-bordered" required>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-8">
                        <button type="submit" class="btn btn-primary px-10">Simpan Tiket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>