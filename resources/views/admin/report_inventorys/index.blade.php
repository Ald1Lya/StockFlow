@extends('layouts.admin-layout')

@section('page-title', 'Pelaporan Barang Gudang')

@section('content')
<section>
    {{-- Header --}}
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Report</p>
            <h1 class="mt-1 text-2xl font-semibold text-stone-900">Pelaporan Kondisi Barang</h1>
            <p class="mt-1 text-sm text-stone-500">{{ $products->total() }} produk terpantau</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">
        <ul class="list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


{{-- Filter --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        @foreach (['' => 'Semua', 'pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
            @php
                $isActive = ($currentStatus ?? '') === $value;
            @endphp
            <a href="{{ route('admin.report_inventorys.index', array_filter(['status' => $value ?: null])) }}"
               class="rounded-md px-3 py-1.5 text-xs font-semibold {{ $isActive ? 'bg-stone-900 text-white' : 'border border-stone-200 bg-white text-stone-700 hover:bg-stone-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-sm">
                <thead class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="w-12 px-5 py-3 font-semibold">No</th>
                        <th class="px-3 py-3 font-semibold">Produk</th>
                        <th class="px-3 py-3 font-semibold">Kategori</th>
                        <th class="px-3 py-3 text-center font-semibold">Jumlah Laporan</th>
                        <th class="px-3 py-3 font-semibold">Pelapor</th>
                        <th class="px-3 py-3 font-semibold">Alasan & Catatan</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-3 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($products as $product)
                    @php $movement = $product->latestStockMovement; @endphp
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-stone-400">
                            {{ ($products->firstItem() ?? 0) + $loop->index }}
                        </td>
                        <td class="px-3 py-3">
                            <div class="font-medium text-stone-900">{{ $product->name }}</div>
                            <div class="font-mono text-xs text-stone-400">{{ $product->sku }}</div>
                        </td>
                        <td class="px-3 py-3 text-stone-500">{{ $product->category?->name ?? '-' }}</td>
                        
                        {{-- Logika Jumlah Disesuaikan: Hanya Pengurangan (Keluar/Rusak) --}}
                        <td class="px-3 py-3 text-center">
                            <div class="font-mono font-semibold text-red-600" title="Akan mengurangi stok jika disetujui">
                                -{{ number_format($movement->quantity, 0, ',', '.') }}
                            </div>
                            <div class="text-[10.5px] text-stone-400 mt-0.5">Sisa Stok: {{ $product->stock }}</div>
                        </td>
                        
                        <td class="px-3 py-3">{{ $movement->user?->name ?? '-' }}</td>
                        
                        <td class="max-w-xs px-3 py-3 text-xs text-stone-500">
                            <div class="line-clamp-2" title="{{ $movement->reason }}">{{ $movement->reason ?: '-' }}</div>
                            @if($movement->approved_note)
                            <div class="mt-1 rounded bg-stone-50 px-2 py-1 text-[10.5px] text-stone-500 border border-stone-100">
                                <span class="font-semibold text-stone-600">Admin:</span> {{ $movement->approved_note }}
                            </div>
                            @endif
                        </td>
                        
                        <td class="px-3 py-3">
                            @if($movement->status === 'pending')
                            <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-xs font-medium text-amber-700">Pending</span>
                            @elseif($movement->status === 'approved')
                            <span class="inline-flex rounded-full bg-green-50 border border-green-200 px-2 py-0.5 text-xs font-medium text-green-700">Disetujui</span>
                            @else
                            <span class="inline-flex rounded-full bg-red-50 border border-red-200 px-2 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                            @endif
                        </td>
                        
                        <td class="whitespace-nowrap px-3 py-3">
                            <div class="font-mono text-xs">{{ $movement->created_at?->format('d/m/Y') }}</div>
                            <div class="font-mono text-[10.5px] text-stone-400">{{ $movement->created_at?->format('H:i') }}</div>
                        </td>
                        
                        <td class="px-5 py-3 text-center">
                            @if($movement->status === 'pending')
                            <button type="button"
                                data-url="{{ route('admin.report_inventorys.update', $movement->id) }}"
                                data-product="{{ $product->name }}"
                                data-quantity="{{ $movement->quantity }}"
                                data-reason="{{ $movement->reason }}"
                                onclick="openProcessModal(this)"
                                class="rounded-md bg-stone-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-stone-700 shadow-sm">
                                Proses
                            </button>
                            @else
                            <span class="text-xs text-stone-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-sm text-stone-500">
                            Belum ada laporan dari gudang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex flex-col gap-3 border-t border-stone-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-stone-500">
            Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
        </p>
        @if($products->hasPages())
        {{ $products->links() }}
        @endif
    </div>

    {{-- Single Modal (Sangat Efisien) --}}
    <div id="process-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity" onclick="closeProcessModal()"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white shadow-2xl">
            <div class="border-b border-stone-100 px-5 py-4">
                <h3 class="text-base font-semibold text-stone-900">Proses Pengajuan</h3>
                <p id="process-modal-info" class="mt-1 text-xs text-stone-500"></p>
            </div>
            <form id="process-form" method="POST" class="p-5">
                @csrf
                @method('PUT')
                
                {{-- Preview Alasan di Dalam Modal agar Admin Ingat --}}
                <div class="mb-4 rounded-md bg-stone-50 p-3 text-xs text-stone-600 border border-stone-100">
                    <strong class="text-stone-800">Alasan Pelapor:</strong>
                    <p id="process-modal-reason" class="mt-1 italic"></p>
                </div>

                <label class="block text-xs font-semibold text-stone-700">
                    Catatan Keputusan <span class="text-red-500">*</span>
                </label>
                <textarea name="approved_note" id="process-note" rows="3" required maxlength="255"
                    class="mt-1.5 w-full rounded-lg border border-stone-300 px-3 py-2.5 text-sm placeholder:text-stone-400 focus:border-stone-900 focus:outline-none focus:ring-1 focus:ring-stone-900 transition-shadow"
                    placeholder="Contoh: Sudah dicek, stok akan dipotong..."></textarea>
                
                <div class="mt-5 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeProcessModal()"
                        class="rounded-lg border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                        Batal
                    </button>
                    <button type="submit" name="action" value="reject"
                        class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 transition">
                        Tolak
                    </button>
                    <button type="submit" name="action" value="approve"
                        class="rounded-lg bg-stone-900 px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800 transition">
                        Setujui & Potong Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
(function () {
    const modal = document.getElementById('process-modal');
    const form  = document.getElementById('process-form');
    const info  = document.getElementById('process-modal-info');
    const reasonPreview = document.getElementById('process-modal-reason');
    const note  = document.getElementById('process-note');

    window.openProcessModal = function (btn) {
        // Set URL Form Update
        form.action = btn.dataset.url;
        
        // Isi text deskripsi di modal
        info.innerHTML = `<strong class="text-stone-900">${btn.dataset.product}</strong> akan dikurangi <strong class="text-red-600">${btn.dataset.quantity} unit</strong>`;
        reasonPreview.textContent = btn.dataset.reason;
        
        // Reset Textarea
        note.value = '';
        
        // Tampilkan Modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus ke input catatan secara otomatis
        setTimeout(() => note.focus(), 100);
    };

    window.closeProcessModal = function () {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    // Tutup modal pakai tombol ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeProcessModal();
        }
    });
})();
</script>
@endsection