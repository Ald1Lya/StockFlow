@extends('layouts.warehouse-layout')

@section('page-title', 'Purchase Order')

@section('content')
<section>
    <x-modal
        :show="session()->has('success')"
        title="Berhasil"
        :message="session('success', '')"
        action-text="Oke, mengerti"
    />

    {{-- Header --}}
    <div class="mb-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Pembelian</p>
        <h1 class="mt-1 text-2xl font-semibold text-stone-900">Purchase Order</h1>
        <p class="mt-1 text-sm text-stone-500">{{ $acceptpos->total() }} PO tersimpan</p>
    </div>

    {{-- Filter bar --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        <form method="GET" action="{{ route('warehouse.acceptpos.index') }}" class="flex flex-1 flex-wrap items-center gap-2">
            {{-- Search utama (PO / supplier) --}}
            <div class="relative w-full sm:w-72">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
                </svg>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    maxlength="100"
                    placeholder="Cari no. PO atau nama supplier..."
                    class="w-full rounded-md border border-stone-300 bg-white py-2 pl-9 pr-3 text-sm text-stone-700 placeholder:text-stone-400 focus:border-stone-500 focus:outline-none focus:ring-2 focus:ring-stone-500/10"
                >
            </div>

            {{-- Filter satuan (dropdown dari daftar resmi) --}}
            <div class="w-full sm:w-40">
                <select
                    name="unit"
                    class="w-full rounded-md border border-stone-300 bg-white py-2 pl-3 pr-8 text-sm text-stone-700 focus:border-stone-500 focus:outline-none focus:ring-2 focus:ring-stone-500/10"
                >
                    <option value="">Semua satuan</option>
                    @foreach ($unitOptions as $option)
                        <option value="{{ $option }}" @selected($unit === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="sort" value="{{ $sort }}">
            <button type="submit" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50">
                Cari
            </button>
        </form>

        @if ($search !== '' || $unit !== '' || $sort !== 'newest')
        <a href="{{ route('warehouse.acceptpos.index') }}" class="px-2 py-2 text-sm font-medium text-stone-500 hover:text-stone-900">
            Reset
        </a>
        @endif

        <div class="ml-auto flex items-center gap-2">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
               class="rounded-md border px-3 py-2 text-sm font-medium transition-colors {{ $sort === 'newest' ? 'border-amber-600 bg-amber-600 text-white' : 'border-stone-300 bg-white text-stone-700 hover:bg-stone-50' }}">
                Paling Baru
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}"
               class="rounded-md border px-3 py-2 text-sm font-medium transition-colors {{ $sort === 'oldest' ? 'border-amber-600 bg-amber-600 text-white' : 'border-stone-300 bg-white text-stone-700 hover:bg-stone-50' }}">
                Paling Lama
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-stone-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="w-12 px-5 py-3 font-semibold">No</th>
                        <th class="px-3 py-3 font-semibold">No. PO</th>
                        <th class="px-3 py-3 font-semibold">Supplier</th>
                        <th class="px-3 py-3 font-semibold">Dibuat Oleh</th>
                        <th class="px-3 py-3 font-semibold">Tanggal</th>
                        <th class="px-3 py-3 text-center font-semibold">Qty</th>
                        <th class="px-3 py-3 font-semibold">Satuan</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse ($acceptpos as $acceptpo)
                    @php
                        $statusStyle = match($acceptpo->status) {
                            'pending'   => 'bg-amber-50 text-amber-700',
                            'approved'  => 'bg-blue-50 text-blue-700',
                            'received'  => 'bg-green-50 text-green-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default     => 'bg-stone-100 text-stone-600',
                        };
                        $statusLabel = match($acceptpo->status) {
                            'pending'   => 'Pending',
                            'approved'  => 'Disetujui',
                            'received'  => 'Diterima',
                            'cancelled' => 'Dibatalkan',
                            default     => ucfirst($acceptpo->status),
                        };
                        $totalQty = $acceptpo->details->sum('quantity');

                        // Ambil satuan dari kolom `pcs` di tabel products
                        $units = $acceptpo->details
                            ->map(fn ($d) => $d->product?->pcs)
                            ->filter()
                            ->unique()
                            ->values();
                    @endphp
                    <tr class="hover:bg-stone-50/60">
                        <td class="px-5 py-3 font-mono text-xs text-stone-400">
                            {{ ($acceptpos->firstItem() ?? 0) + $loop->index }}
                        </td>
                        <td class="px-3 py-3 font-mono text-xs font-medium text-stone-900">
                            {{ $acceptpo->po_number }}
                        </td>
                        <td class="px-3 py-3 font-medium">{{ $acceptpo->supplier->name }}</td>
                        <td class="px-3 py-3 text-stone-500">{{ $acceptpo->user->name }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-stone-500">
                            {{ $acceptpo->purchase_date ? \Carbon\Carbon::parse($acceptpo->purchase_date)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3 py-3 text-center font-mono tabular-nums">
                            {{ number_format($totalQty, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex max-w-[180px] flex-wrap gap-1">
                                @forelse ($units as $u)
                                    <span class="inline-flex rounded bg-stone-100 px-2 py-0.5 text-xs font-medium text-stone-600">
                                        {{ $u }}
                                    </span>
                                @empty
                                    <span class="text-xs text-stone-400">-</span>
                                @endforelse
                            </span>
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $statusStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <form action="{{ route('warehouse.acceptpos.update', $acceptpo) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    @disabled($acceptpo->status !== 'pending')
                                    class="rounded-md border border-stone-300 bg-white px-2 py-1.5 text-xs text-stone-700 focus:border-stone-500 focus:outline-none focus:ring-2 focus:ring-stone-500/10 disabled:cursor-not-allowed disabled:bg-stone-50 disabled:text-stone-400">
                                    <option value="pending"   @selected($acceptpo->status === 'pending')>Pending</option>
                                    <option value="received"  @selected($acceptpo->status === 'received')>Diterima</option>
                                    <option value="cancelled" @selected($acceptpo->status === 'cancelled')>Dibatalkan</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center">
                            <p class="text-sm font-medium text-stone-700">Belum ada purchase order</p>
                            <p class="mt-1 text-sm text-stone-500">
                                {{ ($search !== '' || $unit !== '') ? 'Coba kata kunci / satuan yang berbeda.' : 'PO akan muncul di sini setelah dibuat.' }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col gap-3 border-t border-stone-200 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-stone-500">
                Menampilkan {{ $acceptpos->firstItem() ?? 0 }} - {{ $acceptpos->lastItem() ?? 0 }} dari {{ $acceptpos->total() }} PO
            </p>
            @if ($acceptpos->hasPages())
                {{ $acceptpos->links() }}
            @endif
        </div>
    </div>
</section>
@endsection