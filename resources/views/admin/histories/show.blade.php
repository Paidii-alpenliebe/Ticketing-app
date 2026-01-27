<x-layouts.admin title="Detail Pemesanan">
  <section class="max-w-4xl mx-auto py-12 px-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-white">Detail Pemesanan</h1>
      <div class="text-sm text-gray-500">Order #{{ $order->id }} •
        {{ $order->created_at->format('d M Y H:i') }}
      </div>
    </div>

    <div class="card bg-zinc-900 border border-zinc-800 shadow-md">
      <div class="lg:flex">
        <div class="lg:w-1/3 p-6 border-r border-zinc-800">
          <img
            src="{{ $order->event?->gambar ? asset('images/events/' . $order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
            alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full rounded-lg object-cover mb-4" />
          <h2 class="font-bold text-lg text-white">{{ $order->event?->judul ?? 'Event' }}</h2>
          <p class="text-sm text-zinc-400 mt-1">📍 {{ $order->event?->lokasi ?? '-' }}</p>
          
          <div class="mt-6">
            <h3 class="text-xs uppercase text-zinc-500 font-bold tracking-widest">Informasi Pembeli</h3>
            <p class="text-sm text-white mt-1">{{ $order->user->name }}</p>
            <p class="text-xs text-zinc-400">{{ $order->user->email }}</p>
          </div>
        </div>

        <div class="card-body lg:w-2/3">
          <h3 class="font-bold text-white mb-4">Rincian Tiket</h3>
          <div class="space-y-4">
            @foreach($order->detailOrders as $d)
              <div class="flex justify-between items-center bg-zinc-800/50 p-3 rounded-lg">
                <div>
                  <div class="font-bold text-white">{{ $d->tiket->tipe }}</div>
                  <div class="text-xs text-zinc-400">Harga Satuan: Rp {{ number_format($d->tiket->harga, 0, ',', '.') }}</div>
                  <div class="text-sm text-indigo-400">Qty: {{ $d->jumlah }}</div>
                </div>
                <div class="text-right">
                  <div class="font-bold text-white">Rp {{ number_format($d->subtotal_harga, 0, ',', '.') }}</div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="divider before:bg-zinc-800 after:bg-zinc-800"></div>

          <div class="flex justify-between items-center text-sm mb-2">
            <span class="text-zinc-400">Metode Pembayaran</span>
            <span class="badge badge-outline border-zinc-700 text-zinc-300 font-medium p-3">
               {{ $order->paymentType?->nama_tipe_pembayaran ?? 'N/A' }}
            </span>
          </div>

          <div class="flex justify-between items-center">
            <span class="font-bold text-white text-lg">Total Pembayaran</span>
            <span class="font-extrabold text-2xl text-indigo-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
          </div>

          <div class="mt-8 flex justify-end">
            <a href="{{ route('admin.histories.index') }}" class="btn btn-outline border-zinc-700 hover:bg-zinc-800 text-white">
              Kembali ke Riwayat
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</x-layouts.admin>