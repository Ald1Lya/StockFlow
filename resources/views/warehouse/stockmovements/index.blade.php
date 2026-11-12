@extends('layouts.warehouse-layout')

@section('page-title', 'Manajemen Stok')

@section('content')
<x-modal :show="session()->has('success')" title="Berhasil" :message="session('success', '')"
    action-text="Oke, mengerti" />

<section>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-[#a67b3f]">
                Manajemen Persediaan
            </p>

            <h1 class="mt-1 text-xl font-bold text-[#3a352d]">
                Manajemen Stok
            </h1>

            <p class="mt-1 text-sm text-[#7a7264]">
                Pilih produk untuk mengajukan stok masuk atau melaporkan stok keluar.
            </p>
        </div>

        <div class="text-sm text-[#7a7264]">
            Total produk:
            <span class="font-semibold text-[#3a352d]">
                {{ $products->total() }}
            </span>
        </div>
    </div>

    {{-- Pencarian produk --}}
    <div class="mb-4 rounded-xl border border-[#e2dac8] bg-white p-4">
        <form action="{{ route('warehouse.stockmovements.index') }}" method="GET"
            class="flex flex-wrap items-center gap-3">

            <div class="relative min-w-[220px] flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode, nama dan kategori produk..."
                    class="w-full rounded-lg border border-[#e2dac8] bg-[#f7f4ee] py-2.5 pl-10 pr-4 text-sm text-[#3a352d] outline-none transition focus:border-[#c9934a]">

                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#7a7264]" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                    <path d="m16 16 4 4" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>

            <button type="submit"
                class="rounded-lg bg-[#3a352d] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#51483b]">
                Cari
            </button>

            @if ($search !== '')
            <a href="{{ route('warehouse.stockmovements.index') }}"
                class="rounded-lg border border-[#e2dac8] px-4 py-2.5 text-sm font-medium text-[#7a7264] transition hover:bg-[#f7f4ee]">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel produk --}}
<div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
    <div class="overflow-x-auto">
        <table class="w-full min-w-full table-auto border-collapse text-left text-[12.5px]">
            <thead>
                <tr class="border-b border-[#e2dac8] bg-[#f7f4ee] text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                    <th class="px-5 py-3 font-semibold">No.</th>
                    <th class="px-5 py-3 font-semibold">SKU</th>
                    <th class="px-3 py-3 font-semibold">Nama Produk</th>
                    <th class="px-3 py-3 font-semibold">Kategori</th>
                    <th class="px-3 py-3 font-semibold">Brand</th>
                    <th class="px-3 py-3 font-semibold">Stok</th>
                    <th class="px-3 py-3 font-semibold">Status</th>
                    <th class="px-3 py-3 font-semibold text-center">Yang Tersisa</th>
                    <th class="px-3 py-3 font-semibold">Laporan Terakhir</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                @forelse ($products as $product)
                    @php
                        $latest = $product->latestStockMovement;
                        $isPending = $latest && $latest->status === 'pending';
                    @endphp
                    <tr class="hover:bg-[#f7f4ee]/50">
                        <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">
                            {{ ($products->firstItem() ?? 0) + $loop->index }}
                        </td>
                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">
                            {{ $product->sku }}
                        </td>
                        <td class="px-3 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">{{ $product->brand?->name ?? '-' }}</td>
                        <td class="px-3 py-3 font-mono tnum">
                            {{ $product->stock }} <span class="text-[10px] text-[#a89f8e]">/ min {{ $product->min_stock }}</span>
                        </td>
                        <td class="px-3 py-3">
                            <span class="rounded px-1.5 py-0.5 text-[10.5px] font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-center">
                            @if ($product->stock <= 0)
                                <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                                    Habis
                                </span>
                            @elseif ($product->stock <= $product->min_stock)
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    {{ number_format($product->stock, 0, ',', '.') }} · Menipis
                                </span>
                            @else
                                <span class="font-semibold text-[#3a352d]">
                                    {{ number_format($product->stock, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>

                        {{-- Status Pengecekan Admin Terakhir --}}
                        <td class="px-3 py-3 text-[11.5px]">
                            @if (!$latest)
                                <span class="text-[#a89f8e]">-</span>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-semibold text-rose-700">-{{ $latest->quantity }}</span>
                                    @if ($latest->status === 'pending')
                                        <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-semibold text-amber-800">
                                            Menunggu Admin
                                        </span>
                                    @elseif ($latest->status === 'approved')
                                        <span class="rounded bg-green-100 px-1.5 py-0.5 text-[10px] font-semibold text-green-700">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="rounded bg-red-100 px-1.5 py-0.5 text-[10px] font-semibold text-red-700">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-0.5 max-w-[180px] truncate text-[10.5px] text-[#7a7264]" title="{{ $latest->reason }}">
                                    {{ $latest->reason }}
                                </div>
                            @endif
                        </td>

                        {{-- Tombol Aksi Dinamis (Bisa dipakai berkali-kali setelah diproses Admin) --}}
                        <td class="px-5 py-3 text-right">
                            @if ($isPending)
                                <span class="inline-flex cursor-not-allowed items-center rounded-md border border-[#e2dac8] bg-[#f7f4ee] px-3 py-1.5 text-[11.5px] font-medium text-[#8a8073]">
                                    Menunggu Admin
                                </span>
                            @elseif ($product->stock <= 0)
                                <span class="inline-flex cursor-not-allowed items-center rounded-md border border-[#e2dac8] bg-gray-50 px-3 py-1.5 text-[11.5px] font-medium text-gray-400">
                                    Stok Kosong
                                </span>
                            @else
                                <a href="{{ route('warehouse.stockmovements.create', ['product' => $product->id]) }}"
                                   class="inline-flex items-center rounded-md border border-[#c9934a] bg-white px-3 py-1.5 text-[11.5px] font-medium text-[#8f6224] transition hover:bg-[#c9934a] hover:text-white">
                                    Lapor Kondisi
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-5 py-8 text-center text-[12.5px] text-[#7a7264]">
                            {{ $search !== '' ? 'Produk tidak ditemukan.' : 'Belum ada produk. Tambahkan produk pertama Anda.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</section>
@endsection
