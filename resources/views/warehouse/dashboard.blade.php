@extends('layouts.warehouse-layout')

@section('page-title', 'Dashboard Gudang')

@section('content')
    <!-- ============ MAIN ============ -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- Top bar -->
        <header class="bg-white border-b border-line sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 lg:px-8 h-16 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button class="lg:hidden w-9 h-9 rounded-lg border border-line flex items-center justify-center hover:bg-cream">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-[15px] font-semibold tracking-tight truncate">Dashboard Gudang</h1>
                        <p class="text-[12px] text-muted truncate">
                            Selamat datang, <strong class="text-ink font-medium">{{ auth()->user()->name ?? 'Staff Gudang' }}</strong>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-3">
                    <div class="hidden md:block font-mono text-[11.5px] text-muted px-3 py-2 bg-cream rounded-lg border border-line tracking-wide">
                        {{ strtoupper(now()->locale('id')->translatedFormat('l')) }} · {{ now()->locale('id')->format('d M Y') }}
                    </div>

                    <div class="flex items-center gap-2.5 pl-2 md:pl-3 md:border-l border-line">
                        <div class="w-9 h-9 rounded-full bg-gold-light text-gold-dark flex items-center justify-center font-semibold text-[13px] ring-2 ring-white shadow-sm">
                            {{ strtoupper(mb_substr(auth()->user()->name ?? 'SG', 0, 2)) }}
                        </div>
                        <div class="hidden md:block">
                            <div class="text-[13px] font-medium leading-tight">{{ auth()->user()->name ?? 'Staff Gudang' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-5">

            <!-- ============ STATISTIK CARDS ============ -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Total Produk -->
                <div class="bg-white border border-line p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <div class="text-[11.5px] text-muted font-medium mb-1">Total Produk</div>
                        <div class="text-[24px] font-mono font-semibold text-ink leading-none">{{ number_format($totalProducts) }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-cream flex items-center justify-center text-gold-dark">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                </div>

                <!-- Total Stok -->
                <div class="bg-white border border-line p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <div class="text-[11.5px] text-muted font-medium mb-1">Total Stok</div>
                        <div class="text-[24px] font-mono font-semibold text-ink leading-none">{{ number_format($totalStock) }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-success-bg flex items-center justify-center text-success">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                </div>

                <!-- Stok Menipis -->
                <div class="bg-white border border-line p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <div class="text-[11.5px] text-muted font-medium mb-1">Stok Menipis</div>
                        <div class="text-[24px] font-mono font-semibold text-gold-dark leading-none">{{ number_format($lowStock) }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gold-light flex items-center justify-center text-gold-dark">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>

                <!-- Stok Habis -->
                <div class="bg-white border border-line p-5 rounded-2xl flex items-center justify-between">
                    <div>
                        <div class="text-[11.5px] text-muted font-medium mb-1">Stok Habis</div>
                        <div class="text-[24px] font-mono font-semibold text-danger leading-none">{{ number_format($outOfStock) }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-danger-bg flex items-center justify-center text-danger">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

            </div>

            <!-- ============ TABEL STOK MENIPIS ============ -->
            <div class="bg-white border border-line rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-lineSoft">
                    <div>
                        <div class="text-[14px] font-semibold tracking-tight">Perlu Segera Direstock</div>
                        <div class="text-[11.5px] text-muted mt-0.5">Daftar 5 produk dengan stok terendah</div>
                    </div>
                    <!-- Arahkan ke index inventori Anda -->
                    <a href="{{ route('warehouse.iventorys.index') }}" 
                       class="text-[12.5px] text-gold-dark font-medium hover:text-gold">Kelola stok →</a>
                </div>

                <div class="overflow-x-auto scroll-clean">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="bg-[#fbf9f5] border-b border-lineSoft">
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Produk</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">SKU</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Stok Saat Ini</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Stok Min.</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                @php
                                    // Sesuaikan dengan kolom di tabel products Anda (Controller pakai 'stock')
                                    $currentStock = $product->stock;
                                    $minStock     = $product->min_stock;
                                    $isCritical   = $currentStock <= $minStock;
                                    $initial      = strtoupper(mb_substr($product->name ?? 'XX', 0, 2));
                                    $categoryName = $product->category->name ?? 'Tanpa kategori';
                                @endphp
                                <tr class="border-b border-lineSoft hover:bg-[#fbf9f5]">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">
                                                {{ $initial }}
                                            </div>
                                            <div>
                                                <div class="text-[13px] font-semibold">{{ $product->name }}</div>
                                                <div class="text-[11.5px] text-muted">{{ $categoryName }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-[12px] text-muted">{{ $product->sku }}</td>
                                    <td class="px-5 py-3.5 font-mono font-semibold tnum {{ $isCritical ? 'text-danger' : 'text-gold-dark' }}">
                                        {{ number_format($currentStock) }}
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-muted tnum">{{ number_format($minStock) }}</td>
                                    <td class="px-5 py-3.5">
                                        @if($isCritical)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-bg text-danger text-[11px] font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kritis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-light text-gold-dark text-[11px] font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Menipis
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-[12.5px] text-muted">
                                        Semua stok dalam kondisi aman 🎉
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection