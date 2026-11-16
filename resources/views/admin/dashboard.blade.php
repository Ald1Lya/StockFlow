@extends('layouts.admin-layout')

@section('page-title', 'Dashboard')

@section('content')
<section x-data="{
    chartMode: 'monthly',
    charts: {
        monthly: {{ Js::from($monthlyChart) }},
        weekly: {{ Js::from($weeklyChart) }}
    },
    get activeChart() {
        return this.charts[this.chartMode];
    }
}">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Overview</div>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Ringkasan Operasional</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">Data per <span class="font-medium text-[#161410]">{{ now()->translatedFormat('d F Y') }}</span> · real-time dari sistem.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.report_sales.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                Laporan Penjualan
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Pendapatan Bulan Ini -->
        <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
            <div class="flex items-start justify-between">
                <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Pendapatan Bulan Ini</div>
                <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $revenueGrowth >= 0 ? 'bg-[#5a7a5a]/10 text-[#4a6a4a]' : 'bg-[#a04a3a]/10 text-[#a04a3a]' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%
                </span>
            </div>
            <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">
                Rp {{ number_format($currentRevenue, 0, ',', '.') }}
            </div>
            <div class="mt-1 text-[11px] text-[#a89f8e]">vs bulan lalu · Rp {{ number_format($lastMonthRevenue, 0, ',', '.') }}</div>
        </div>

        <!-- Transaksi Penjualan -->
        <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
            <div class="flex items-start justify-between">
                <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Transaksi Kasir</div>
                <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $orderGrowth >= 0 ? 'bg-[#5a7a5a]/10 text-[#4a6a4a]' : 'bg-[#a04a3a]/10 text-[#a04a3a]' }}">
                    {{ $orderGrowth >= 0 ? '+' : '' }}{{ $orderGrowth }}%
                </span>
            </div>
            <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">{{ number_format($currentOrders, 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-[#a89f8e]">{{ $pendingPurchases }} PO menunggu diterima gudang</div>
        </div>

        <!-- Produk Aktif -->
        <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
            <div class="flex items-start justify-between">
                <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Produk Aktif</div>
                <span class="rounded bg-[#161410]/5 px-1.5 py-0.5 text-[10px] font-semibold text-[#3a352d]">{{ $activeProductsCount }} SKU</span>
            </div>
            <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">{{ $activeProductsCount }}</div>
            <div class="mt-1 text-[11px] text-[#a89f8e]">{{ $categoriesCount }} kategori material</div>
        </div>

        <!-- Stok Rendah -->
        <div class="rounded-lg border border-[#c9934a]/40 bg-[#fdf9f0] p-4">
            <div class="flex items-start justify-between">
                <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#a67b3f]">Stok Rendah</div>
                <span class="rounded bg-[#c9934a]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#a67b3f]">Perlu restock</span>
            </div>
            <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">{{ $lowStockCount }}</div>
            <div class="mt-1 text-[11px] text-[#a67b3f]/80">di bawah batas minimum</div>
        </div>
    </div>

    <!-- Chart + Top Products -->
    <div class="mt-4 grid gap-4 xl:grid-cols-3">
        <!-- Chart Dinamis -->
        <div class="rounded-lg border border-[#e2dac8] bg-white p-5 xl:col-span-2">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-[13px] font-semibold text-[#161410]">Tren Pendapatan</div>
                    <div class="text-[11.5px] text-[#8a8073]" x-text="activeChart.subtitle"></div>
                </div>
                <div class="flex gap-1 rounded-md border border-[#e2dac8] p-0.5">
                    <button
                        type="button"
                        @click="chartMode = 'monthly'"
                        :class="chartMode === 'monthly' ? 'bg-[#161410] text-[#ece7dd] font-semibold' : 'text-[#8a8073] hover:bg-[#f7f4ee] font-medium'"
                        class="rounded px-2 py-0.5 text-[10.5px] transition-colors"
                    >
                        Bulan
                    </button>
                    <button
                        type="button"
                        @click="chartMode = 'weekly'"
                        :class="chartMode === 'weekly' ? 'bg-[#161410] text-[#ece7dd] font-semibold' : 'text-[#8a8073] hover:bg-[#f7f4ee] font-medium'"
                        class="rounded px-2 py-0.5 text-[10.5px] transition-colors"
                    >
                        Hari
                    </button>
                </div>
            </div>

            <div class="mt-5">
                <svg viewBox="0 0 600 210" class="w-full" preserveAspectRatio="none" style="height:210px">
                    <defs>
                        <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c9934a" stop-opacity="0.22"/>
                            <stop offset="100%" stop-color="#c9934a" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <!-- grid lines -->
                    <line x1="40" y1="30" x2="560" y2="30" stroke="#e2dac8" stroke-dasharray="3 3"/>
                    <line x1="40" y1="70" x2="560" y2="70" stroke="#e2dac8" stroke-dasharray="3 3"/>
                    <line x1="40" y1="110" x2="560" y2="110" stroke="#e2dac8" stroke-dasharray="3 3"/>
                    <line x1="40" y1="150" x2="560" y2="150" stroke="#e2dac8" stroke-dasharray="3 3"/>
                    <line x1="40" y1="180" x2="560" y2="180" stroke="#e2dac8"/>

                    <!-- area & line dinamis -->
                    <path :d="activeChart.areaPath" fill="url(#areaFill)"/>
                    <polyline :points="activeChart.polyline"
                              fill="none" stroke="#c9934a" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>

                    <!-- titik aktif terakhir -->
                    <circle :cx="activeChart.lastPoint.x" :cy="activeChart.lastPoint.y" r="4" fill="#161410"/>
                    <circle :cx="activeChart.lastPoint.x" :cy="activeChart.lastPoint.y" r="8" fill="#c9934a" opacity="0.2"/>

                    <!-- label X dinamis -->
                    <g fill="#8a8073" font-family="ui-sans-serif" font-size="10">
                        <template x-for="pt in activeChart.points" :key="pt.label">
                            <text :x="pt.x" y="200" text-anchor="middle" x-text="pt.label"></text>
                        </template>
                    </g>

                    <!-- label Y dinamis -->
                    <g fill="#a89f8e" font-family="ui-monospace" font-size="9">
                        <template x-for="tick in activeChart.yTicks" :key="tick.y">
                            <text x="32" :y="tick.y" text-anchor="end" x-text="tick.label"></text>
                        </template>
                    </g>
                </svg>
            </div>
        </div>

        <!-- Top Products -->
        <div class="rounded-lg border border-[#e2dac8] bg-white p-5">
            <div class="flex items-center justify-between">
                <div class="text-[13px] font-semibold text-[#161410]">Produk Terlaris</div>
                <a href="{{ route('admin.products.index') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Semua</a>
            </div>
            <ul class="mt-4 space-y-3">
                @forelse ($topProducts as $item)
                    <li class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-[12.5px] font-medium text-[#161410]">{{ $item->product?->name ?? 'Produk Dihapus' }}</div>
                            <div class="font-mono text-[10.5px] text-[#a89f8e]">{{ $item->product?->sku ?? '-' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">{{ number_format($item->total_sold, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-[#a89f8e]">{{ $item->product?->pcs ?? 'terjual' }}</div>
                        </div>
                    </li>
                @empty
                    <li class="py-6 text-center text-[12px] text-[#8a8073]">Belum ada data penjualan produk.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Recent + Low stock -->
    <div class="mt-4 grid gap-4 xl:grid-cols-3">
        <!-- Recent transactions -->
        <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white xl:col-span-2">
            <div class="flex items-center justify-between border-b border-[#e2dac8] px-5 py-3.5">
                <div class="text-[13px] font-semibold text-[#161410]">Transaksi Terbaru</div>
                <a href="{{ route('admin.report_sales.index') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[12.5px]">
                    <thead>
                        <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                            <th class="px-5 py-2.5 font-semibold">Invoice</th>
                            <th class="px-3 py-2.5 font-semibold">Kasir</th>
                            <th class="px-3 py-2.5 font-semibold">Total</th>
                            <th class="px-3 py-2.5 font-semibold">Status</th>
                            <th class="px-5 py-2.5 text-right font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                        @forelse ($recentSales as $sale)
                            <tr class="hover:bg-[#f7f4ee]/50">
                                <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">{{ $sale->invoice_number }}</td>
                                <td class="px-3 py-3">{{ $sale->user?->name ?? '-' }}</td>
                                <td class="px-3 py-3 font-mono tnum">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td class="px-3 py-3"><span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#4a6a4a]">Lunas</span></td>
                                <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-[12px] text-[#8a8073]">Belum ada transaksi kasir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low stock -->
        <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
            <div class="flex items-center justify-between border-b border-[#e2dac8] px-5 py-3.5">
                <div class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#c9934a]"></span>
                    <div class="text-[13px] font-semibold text-[#161410]">Stok Rendah</div>
                </div>
                <a href="{{ route('admin.report_inventorys.index') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Detail</a>
            </div>
            <ul class="divide-y divide-[#e2dac8]/70">
                @forelse ($lowStockList as $item)
                    <li class="flex items-center justify-between px-5 py-3">
                        <div>
                            <div class="text-[12.5px] font-medium text-[#161410]">{{ $item->name }}</div>
                            <div class="font-mono text-[10.5px] text-[#a89f8e]">{{ $item->sku }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-[12px] font-semibold tnum text-[#a67b3f]">{{ $item->stock }} {{ $item->pcs }}</div>
                            <div class="text-[10px] text-[#a89f8e]">/ min {{ $item->min_stock }}</div>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-[12px] text-[#8a8073]">Semua stok produk aman.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
@endsection