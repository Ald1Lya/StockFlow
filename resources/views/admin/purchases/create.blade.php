@extends('layouts.admin-layout')

@section('page-title', 'Purchase Order Baru')

@section('content')
<section class="mx-auto max-w-3xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">
            Pembelian / Buat PO
        </div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Purchase Order Baru</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">
            Nomor PO dibuat otomatis dari tanggal. Status awal selalu <strong>Pending</strong> — staf gudang yang mengubah ke Diterima saat barang tiba.
        </p>
    </div>

    @if ($errors->any())
        <x-alert type="error" class="mb-4">
            <p class="font-semibold">PO belum tersimpan. Periksa kembali isian berikut:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('admin.purchases.store') }}" method="POST" id="po-form">
        @csrf

        {{-- Info PO --}}
        <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
            <h2 class="mb-4 text-[12px] font-semibold uppercase tracking-wider text-[#8a8073]">Informasi PO</h2>

            <div class="grid gap-4 sm:grid-cols-2">

                {{-- Supplier — search combobox --}}
                <div>
                    <label for="supplier-search" class="text-[12px] font-medium text-[#3a352d]">Supplier</label>
                    <div class="relative mt-1.5">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8073]"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
                        </svg>
                        <input
                            id="supplier-search"
                            type="search"
                            placeholder="Ketik nama supplier..."
                            autocomplete="off"
                            class="w-full rounded-md border border-[#e2dac8] bg-white py-2 pl-9 pr-3 text-[13px] text-[#3a352d] placeholder:text-[#a89f8e] focus:border-[#c9934a] focus:outline-none @error('supplier_id') border-red-400 @enderror"
                            id="supplier-search"
                        >
                        <input type="hidden" name="supplier_id" id="supplier-id" value="{{ old('supplier_id') }}">
                        <div id="supplier-results"
                            class="absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg">
                        </div>
                    </div>
                    @error('supplier_id')
                        <span class="mt-1 block text-[11px] text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tanggal --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Tanggal Pembelian
                    <input type="date" name="purchase_date"
                        value="{{ old('purchase_date', now()->format('Y-m-d')) }}"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none @error('purchase_date') border-red-400 @enderror">
                    @error('purchase_date')
                        <span class="mt-1 block text-[11px] text-red-500">{{ $message }}</span>
                    @enderror
                    <p class="mt-1 text-[11px] text-[#a09880]">
                        No. PO: <span id="po-preview" class="font-mono">PO-{{ now()->format('mdy') }}</span>
                    </p>
                </label>

            </div>
        </div>

        {{-- Item Produk --}}
        <div class="mt-4 rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-[12px] font-semibold uppercase tracking-wider text-[#8a8073]">Item Produk</h2>
                <button type="button" id="btn-add-item"
                    class="inline-flex items-center gap-1.5 rounded-md border border-[#e2dac8] px-3 py-1.5 text-[12px] font-medium text-[#3a352d] hover:border-[#c9934a] hover:text-[#c9934a]">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Item
                </button>
            </div>

            @error('items')
                <p class="mb-3 text-[11px] text-red-500">{{ $message }}</p>
            @enderror

            {{-- Header kolom --}}
            <div class="hidden grid-cols-[1fr_80px_140px_100px_32px] gap-2 border-b border-[#e2dac8] pb-2 text-[10.5px] font-semibold uppercase tracking-wider text-[#8a8073] sm:grid">
                <span>Produk</span>
                <span>Qty</span>
                <span>Harga Beli (Rp)</span>
                <span class="text-right">Subtotal</span>
                <span></span>
            </div>

            <div id="items-container" class="mt-2 flex flex-col gap-2"></div>

            <p id="items-empty" class="py-6 text-center text-[12.5px] text-[#a09880]">
                Belum ada item. Klik <strong>Tambah Item</strong> untuk mulai.
            </p>

            <div class="mt-4 flex justify-end border-t border-[#e2dac8] pt-4">
                <div class="text-right">
                    <span class="text-[12px] text-[#7a7264]">Total Amount</span>
                    <div class="mt-0.5 font-mono text-lg font-semibold text-[#161410]" id="total-display">Rp 0</div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('admin.purchases.index') }}"
                class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                Batal
            </a>
            <button type="submit"
                class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                Buat Purchase Order
            </button>
        </div>

    </form>
</section>






{{-- Template baris item --}}
<template id="item-row-template">
    <div class="item-row grid grid-cols-[1fr_80px_140px_100px_32px] items-start gap-2">
        <div class="relative">
            <input type="search" placeholder="Cari nama atau SKU produk..." autocomplete="off"
                class="item-product-search w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            <input type="hidden" name="items[__INDEX__][product_id]" class="item-product-id">
            <div class="item-product-results absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg"></div>
        </div>
        <input type="number" name="items[__INDEX__][quantity]" min="1" value="1"
            class="item-qty w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
        <input type="number" name="items[__INDEX__][unit_cost]" min="0" value="0"
            class="item-cost w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
        <div class="item-subtotal flex items-center justify-end pt-2 font-mono text-[12.5px] text-[#3a352d]">Rp 0</div>
        <button type="button" class="btn-remove-item mt-1.5 flex h-8 w-8 items-center justify-center rounded-md border border-red-200 text-red-500 hover:bg-red-50">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>

<script>
(function () {
    const productSearchUrl = "{{ route('admin.purchases.create') }}";
    const container        = document.getElementById('items-container');
    const emptyState       = document.getElementById('items-empty');
    const totalDisplay     = document.getElementById('total-display');
    const template         = document.getElementById('item-row-template');
    let itemIndex          = 0;

    // ── Preview no. PO dari tanggal ──────────────────────────────────────────
    const dateInput = document.querySelector('[name="purchase_date"]');
    const poPreview = document.getElementById('po-preview');
    dateInput?.addEventListener('change', () => {
        const d = new Date(dateInput.value);
        if (!isNaN(d)) {
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            const yy = String(d.getFullYear()).slice(-2);
            poPreview.textContent = 'PO-' + mm + dd + yy;
        }
    });

    // ── Search supplier ──────────────────────────────────────────────────────
    // Data supplier diload dari PHP ke JS — tidak perlu request AJAX tambahan.
    const suppliers = @json($suppliers);
    const supplierSearch  = document.getElementById('supplier-search');
    const supplierIdInput = document.getElementById('supplier-id');
    const supplierResults = document.getElementById('supplier-results');

    // Tampilkan nama supplier yang sudah dipilih sebelumnya (saat validasi gagal).
    const oldSupplierId = supplierIdInput.value;
    if (oldSupplierId) {
        const found = suppliers.find(s => String(s.id) === String(oldSupplierId));
        if (found) supplierSearch.value = found.name;
    }

    const renderSuppliers = (list) => {
        supplierResults.replaceChildren();
        if (!list.length) {
            const p = document.createElement('p');
            p.className = 'px-3 py-2 text-[12px] text-[#a09880]';
            p.textContent = 'Supplier tidak ditemukan.';
            supplierResults.append(p);
        } else {
            list.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-full px-3 py-2 text-left text-[13px] text-[#3a352d] hover:bg-[#f7f4ee]';
                btn.textContent = s.name;
                btn.addEventListener('pointerdown', e => e.preventDefault());
                btn.addEventListener('click', () => {
                    supplierIdInput.value = s.id;
                    supplierSearch.value  = s.name;
                    supplierResults.classList.add('hidden');
                });
                supplierResults.append(btn);
            });
        }
        supplierResults.classList.remove('hidden');
    };

    supplierSearch.addEventListener('focus', () => {
        renderSuppliers(suppliers);
    });
    supplierSearch.addEventListener('input', () => {
        supplierIdInput.value = '';
        const q = supplierSearch.value.toLowerCase();
        renderSuppliers(suppliers.filter(s => s.name.toLowerCase().includes(q)));
    });
    supplierSearch.addEventListener('blur', () => {
        setTimeout(() => supplierResults.classList.add('hidden'), 150);
    });

    // ── Format rupiah ────────────────────────────────────────────────────────
    const formatRp = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

    // ── Hitung ulang total ───────────────────────────────────────────────────
    const recalcTotal = () => {
        let total = 0;
        container.querySelectorAll('.item-row').forEach(row => {
            const qty  = parseInt(row.querySelector('.item-qty').value)  || 0;
            const cost = parseInt(row.querySelector('.item-cost').value) || 0;
            const sub  = qty * cost;
            row.querySelector('.item-subtotal').textContent = formatRp(sub);
            total += sub;
        });
        totalDisplay.textContent = formatRp(total);
    };

    // ── Search produk per baris item ─────────────────────────────────────────
    const attachProductSearch = (row) => {
        const searchInput = row.querySelector('.item-product-search');
        const hiddenId    = row.querySelector('.item-product-id');
        const resultsBox  = row.querySelector('.item-product-results');
        const costInput   = row.querySelector('.item-cost');
        let debounce, controller;

        const closeResults = () => {
            resultsBox.classList.add('hidden');
            resultsBox.replaceChildren();
        };

        const selectProduct = (p) => {
            hiddenId.value    = p.id;
            searchInput.value = p.name + ' (' + p.sku + ')';
            costInput.value   = p.cost_price;
            recalcTotal();
            closeResults();
        };

        const doSearch = (q) => {
            clearTimeout(debounce);
            controller?.abort();
            debounce = setTimeout(async () => {
                controller = new AbortController();
                try {
                    const res  = await fetch(productSearchUrl + '?search=' + encodeURIComponent(q), {
                        signal: controller.signal,
                        headers: { Accept: 'application/json' },
                    });
                    const data = await res.json();
                    resultsBox.replaceChildren();
                    if (!data.data.length) {
                        const el = document.createElement('p');
                        el.className = 'px-3 py-2 text-[12px] text-[#a09880]';
                        el.textContent = 'Produk tidak ditemukan.';
                        resultsBox.append(el);
                    } else {
                        data.data.forEach(p => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'w-full px-3 py-2 text-left text-[12.5px] hover:bg-[#f7f4ee]';
                            btn.innerHTML = '<span class="font-medium text-[#3a352d]">' + p.name + '</span>'
                                + ' <span class="font-mono text-[11px] text-[#a09880]">' + p.sku + '</span>';
                            btn.addEventListener('pointerdown', e => e.preventDefault());
                            btn.addEventListener('click', () => selectProduct(p));
                            resultsBox.append(btn);
                        });
                    }
                    resultsBox.classList.remove('hidden');
                } catch (e) { /* abort ok */ }
            }, 200);
        };

        searchInput.addEventListener('focus', () => doSearch(searchInput.value));
        searchInput.addEventListener('input', () => { hiddenId.value = ''; doSearch(searchInput.value); });
        searchInput.addEventListener('blur',  () => setTimeout(closeResults, 150));
    };

    // ── Tambah baris item ────────────────────────────────────────────────────
    const addItem = () => {
        const clone = template.content.cloneNode(true);
        const row   = clone.querySelector('.item-row');

        row.innerHTML = row.innerHTML.replaceAll('__INDEX__', itemIndex++);
        row.querySelector('.item-qty').addEventListener('input', recalcTotal);
        row.querySelector('.item-cost').addEventListener('input', recalcTotal);
        row.querySelector('.btn-remove-item').addEventListener('click', () => {
            row.remove();
            recalcTotal();
            emptyState.classList.toggle('hidden', container.children.length > 0);
        });

        attachProductSearch(row);
        container.append(row);
        emptyState.classList.add('hidden');
        row.querySelector('.item-product-search').focus();
    };

    document.getElementById('btn-add-item').addEventListener('click', addItem);
    addItem(); // mulai dengan 1 baris kosong
})();
</script>
@endsection
