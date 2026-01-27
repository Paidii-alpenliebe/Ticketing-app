<x-layouts.app>
  <section class="max-w-6xl mx-auto py-12 px-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Riwayat Pembelian</h1>
    </div>

    <div class="space-y-4">
      @forelse($orders as $order)
        <article class="card lg:card-side bg-base-100 shadow-md overflow-hidden">
          <figure class="lg:w-48">
            <img
              src="{{ $order->event?->gambar ? asset($order->event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
              alt="{{ $order->event?->judul ?? 'Event' }}" class="w-full h-full object-cover" />
          </figure>

          <div class="card-body flex-row justify-between items-center">
            <div>
              <div class="font-bold text-lg">Order #{{ $order->id }}</div>
              <div class="text-sm text-gray-500 mt-1">{{ $order->order_date->translatedFormat('d F Y, H:i') }}</div>
              <div class="text-md font-medium mt-2 text-primary">{{ $order->event?->judul ?? 'Event' }}</div>
              
              <div class="mt-3">
                <span class="text-xs uppercase tracking-wider text-gray-400 block">Metode Pembayaran:</span>
                <span class="badge badge-ghost font-semibold mt-1">
                  {{ $order->paymentType?->nama_tipe_pembayaran ?? 'N/A' }}
                </span>
              </div>
            </div>

            <div class="text-right">
              <div class="text-sm text-gray-400 mb-1">Total Harga</div>
              <div class="font-bold text-xl text-indigo-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
              <a href="{{ route('orders.show', $order) }}" class="btn btn-primary mt-4 text-white !bg-blue-900 border-none px-6">Lihat Detail</a>
            </div>
          </div>
        </article>
      @empty
        <div class="alert alert-info">Anda belum memiliki pesanan.</div>
      @endforelse
    </div>
  </section>
</x-layouts.app>