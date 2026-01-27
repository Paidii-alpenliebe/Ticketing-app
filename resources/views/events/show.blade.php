<x-layouts.app>
  <section class="max-w-7xl mx-auto py-12 px-6">
    <nav class="mb-6">
      <div class="breadcrumbs text-sm">
        <ul>
          <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
          <li><a href="#" class="link link-neutral">Event</a></li>
          <li>{{ $event->judul }}</li>
        </ul>
      </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow-sm border border-base-200">
          <figure>
            <img src="{{ $event->gambar ? asset('storage/' . $event->gambar) : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}" 
                 alt="{{ $event->judul }}" class="w-full h-96 object-cover" />
          </figure>
          <div class="card-body">
            <div class="flex justify-between items-start gap-4">
              <div>
                <h1 class="text-3xl font-extrabold text-white">{{ $event->judul }}</h1>
                <p class="text-sm text-gray-400 mt-2">
                  {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }} • 📍 {{ $event->lokasi }}
                </p>

                <div class="mt-4 flex gap-2 items-center">
                  <span class="badge badge-primary">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
                  <span class="badge badge-outline">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
                </div>
              </div>
            </div>

            <p class="mt-6 text-gray-300 leading-relaxed">{{ $event->deskripsi }}</p>

            <div class="divider"></div>

            <h3 class="text-xl font-bold text-white mb-4">Pilih Tiket</h3>

            <div class="space-y-4 text-white">
              @forelse($event->tikets as $tiket)
              <div class="card card-side bg-zinc-900 border border-zinc-800 shadow-sm p-4 items-center">
                <div class="flex-1">
                  <h4 class="font-bold text-lg text-white">{{ $tiket->tipe }}</h4>
                  <p class="text-sm text-gray-400">Stok: <span id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span></p>
                </div>

                <div class="w-48 text-right">
                  <div class="text-lg font-bold text-white">
                    {{ $tiket->harga ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                  </div>

                  <div class="mt-3 flex items-center justify-end gap-2 text-white">
                    <button type="button" class="btn btn-sm btn-circle btn-outline border-zinc-700" data-action="dec" data-id="{{ $tiket->id }}">−</button>
                    <input id="qty-{{ $tiket->id }}" type="number" min="0" max="{{ $tiket->stok }}" value="0"
                           class="input input-bordered input-sm w-16 text-center bg-zinc-800 border-zinc-700" data-id="{{ $tiket->id }}" />
                    <button type="button" class="btn btn-sm btn-circle btn-outline border-zinc-700" data-action="inc" data-id="{{ $tiket->id }}">+</button>
                  </div>

                  <div class="text-xs text-gray-500 mt-2 italic">Subtotal: <span id="subtotal-{{ $tiket->id }}">Rp 0</span></div>
                </div>
              </div>
              @empty
              <div class="alert alert-info">Tiket belum tersedia untuk acara ini.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <aside class="lg:col-span-1">
        <div class="card sticky top-24 p-6 bg-zinc-900 border border-zinc-800 shadow-lg text-white">
          <h4 class="font-bold text-lg border-b border-zinc-800 pb-3">Ringkasan Pembelian</h4>

          <div class="mt-4 space-y-3">
            <div class="flex justify-between text-sm text-gray-400">
              <span>Total Item</span>
              <span id="summaryItems" class="font-bold text-white">0</span>
            </div>
            <div class="flex justify-between text-xl font-bold text-white">
              <span>Total Harga</span>
              <span id="summaryTotal">Rp 0</span>
            </div>
          </div>

          <div class="divider border-zinc-800"></div>

          <div id="selectedList" class="space-y-2 text-sm text-gray-300">
            <p class="text-gray-500 italic text-center">Belum ada tiket dipilih</p>
          </div>

          @auth
            <button id="checkoutButton" class="btn btn-primary btn-block mt-8 !bg-indigo-600 border-none text-white font-bold" onclick="openCheckout()" disabled>
              Lanjutkan ke Pembayaran
            </button>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-8 text-white">Login untuk Checkout</a>
          @endauth
        </div>
      </aside>
    </div>

    <dialog id="checkout_modal" class="modal">
      <div class="modal-box bg-zinc-900 border border-zinc-800 text-white">
        <h3 class="font-bold text-xl border-b border-zinc-800 pb-4">Konfirmasi Pembelian</h3>
        
        <div class="mt-6 space-y-4">
          <div id="modalItems" class="text-sm space-y-2">
            <p class="text-gray-500 italic">Belum ada item.</p>
          </div>

          <div class="divider border-zinc-800"></div>

          <div class="form-control w-full">
            <label class="label">
              <span class="label-text font-bold text-gray-300">Pilih Metode Pembayaran</span>
            </label>
            <select id="payment_type_id" class="select select-bordered w-full bg-zinc-800 border-zinc-700 text-white" required>
              <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
              @foreach($paymentTypes as $payment)
                <option value="{{ $payment->id }}">{{ $payment->nama_tipe_pembayaran }}</option>
              @endforeach
            </select>
          </div>

          <div class="flex justify-between items-center bg-zinc-800 p-4 rounded-lg mt-4">
            <span class="font-bold">Total Bayar</span>
            <span class="font-extrabold text-xl text-indigo-400" id="modalTotal">Rp 0</span>
          </div>
        </div>

        <div class="modal-action">
          <form method="dialog">
            <button class="btn btn-ghost">Batal</button>
          </form>
          <button type="button" class="btn btn-primary !bg-indigo-600 border-none text-white px-8" id="confirmCheckout">Bayar Sekarang</button>
        </div>
      </div>
    </dialog>
  </section>

  <script>
    (function () {
      const formatRupiah = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');

      const tickets = {
        @foreach($event->tikets as $tiket)
          {{ $tiket->id }}: {
            id: {{ $tiket->id }},
            price: {{ $tiket->harga ?? 0 }},
            stock: {{ $tiket->stok }},
            tipe: "{{ e($tiket->tipe) }}"
          },
        @endforeach
      };

      const summaryItemsEl = document.getElementById('summaryItems');
      const summaryTotalEl = document.getElementById('summaryTotal');
      const selectedListEl = document.getElementById('selectedList');
      const checkoutButton = document.getElementById('checkoutButton');

      function updateSummary() {
        let totalQty = 0;
        let totalPrice = 0;
        let selectedHtml = '';

        Object.values(tickets).forEach(t => {
          const qtyInput = document.getElementById('qty-' + t.id);
          if (!qtyInput) return;
          const qty = Number(qtyInput.value || 0);
          if (qty > 0) {
            totalQty += qty;
            totalPrice += qty * t.price;
            selectedHtml += `
              <div class="flex justify-between items-center py-1 border-b border-zinc-800 last:border-0">
                <span>${t.tipe} <small class="text-zinc-500">x${qty}</small></span>
                <span class="font-semibold text-zinc-300">${formatRupiah(qty * t.price)}</span>
              </div>`;
          }
        });

        summaryItemsEl.textContent = totalQty;
        summaryTotalEl.textContent = formatRupiah(totalPrice);
        selectedListEl.innerHTML = selectedHtml || '<p class="text-gray-500 italic text-center">Belum ada tiket dipilih</p>';
        checkoutButton.disabled = totalQty === 0;
      }

      // Quantity Handlers
      document.querySelectorAll('[data-action="inc"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const id = e.currentTarget.dataset.id;
          const input = document.getElementById('qty-' + id);
          const info = tickets[id];
          if (Number(input.value) < info.stock) {
            input.value = Number(input.value) + 1;
            updateTicketSubtotal(id);
            updateSummary();
          }
        });
      });

      document.querySelectorAll('[data-action="dec"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const id = e.currentTarget.dataset.id;
          const input = document.getElementById('qty-' + id);
          if (Number(input.value) > 0) {
            input.value = Number(input.value) - 1;
            updateTicketSubtotal(id);
            updateSummary();
          }
        });
      });

      function updateTicketSubtotal(id) {
        const t = tickets[id];
        const qty = Number(document.getElementById('qty-' + id).value || 0);
        const subtotalEl = document.getElementById('subtotal-' + id);
        if (subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);
      }

      // Modal Logic
      window.openCheckout = function () {
        const modalItems = document.getElementById('modalItems');
        const modalTotal = document.getElementById('modalTotal');
        let itemsHtml = '';
        let total = 0;

        Object.values(tickets).forEach(t => {
          const qty = Number(document.getElementById('qty-' + t.id).value || 0);
          if (qty > 0) {
            itemsHtml += `
              <div class="flex justify-between">
                <span>${t.tipe} (x${qty})</span>
                <span class="font-bold">${formatRupiah(qty * t.price)}</span>
              </div>`;
            total += qty * t.price;
          }
        });

        modalItems.innerHTML = itemsHtml;
        modalTotal.textContent = formatRupiah(total);
        document.getElementById('checkout_modal').showModal();
      }

      // Final Submission
      document.getElementById('confirmCheckout').addEventListener('click', async () => {
        const btn = document.getElementById('confirmCheckout');
        const paymentTypeId = document.getElementById('payment_type_id').value;

        if (!paymentTypeId) {
          alert('Silakan pilih metode pembayaran terlebih dahulu');
          return;
        }

        btn.setAttribute('disabled', 'disabled');
        btn.textContent = 'Memproses...';

        const items = [];
        Object.values(tickets).forEach(t => {
          const qty = Number(document.getElementById('qty-' + t.id).value || 0);
          if (qty > 0) items.push({ tiket_id: t.id, jumlah: qty });
        });

        try {
          const res = await fetch("{{ route('orders.store') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
              event_id: {{ $event->id }}, 
              payment_type_id: paymentTypeId, 
              items 
            })
          });

          if (!res.ok) throw new Error(await res.text() || 'Gagal membuat pesanan');

          const data = await res.json();
          window.location.href = data.redirect || '{{ route('orders.index') }}';
        } catch (err) {
          console.error(err);
          alert('Terjadi kesalahan: ' + err.message);
          btn.removeAttribute('disabled');
          btn.textContent = 'Bayar Sekarang';
        }
      });

      updateSummary();
    })();
  </script>
</x-layouts.app>