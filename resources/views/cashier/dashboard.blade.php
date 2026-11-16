@extends('layouts.cashier-layout')

@section('page-title', 'Dashboard Kasir')

@section('content')
<header class="bg-white border-b border-[#e2dac8] h-16 flex items-center justify-between px-8 sticky top-0 z-10">
    <h1 class="text-[16px] font-semibold tracking-tight">Dashboard Kasir</h1>
    <div class="text-right">
        <div class="text-[11px] text-[#8a8073]">Hari Ini</div>
        <div class="font-mono text-[13px] font-medium">{{ now()->translatedFormat('d M Y') }}</div>
    </div>
</header>

<div class="p-8 space-y-8">
    <!-- Banner Aksi Cepat -->
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-[#161410] p-6 text-white shadow-sm">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-wider text-[#c9934a]">Shift Kasir</div>
            <h2 class="mt-1 text-xl font-bold">Halo, {{ Auth::user()->name ?? 'Kasir' }}!</h2>
            <p class="mt-1 text-[13px] text-[#8a8073]">Siap melayani transaksi penjualan toko bangunan hari ini.</p>
        </div>
        <a href="{{ route('cashier.pos.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-[#c9934a] px-5 py-3 text-sm font-bold text-[#161410] hover:bg-[#d8a35a] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Buka Mesin Kasir (POS)
        </a>
    </div>

    <!-- 3 Kartu Statistik Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- <div class="rounded-xl border border-[#e2dac8] bg-white p-5">
            <div class="text-[12px] font-medium text-[#7a7264]">Total Omzet Toko (Hari Ini)</div>
            <div class="mt-2 font-mono text-2xl font-bold text-[#161410]">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </div> --}}
        </div>

        <div class="rounded-xl border border-[#e2dac8] bg-white p-5">
            <div class="text-[12px] font-medium text-[#7a7264]">Total Struk Toko (Hari Ini)</div>
            <div class="mt-2 font-mono text-2xl font-bold text-[#161410]">
                {{ $todayTransactions }} <span class="text-xs font-sans font-normal text-[#8a8073]">transaksi</span>
            </div>
        </div>

        <div class="rounded-xl border border-[#e2dac8] bg-white p-5">
            <div class="text-[12px] font-medium text-[#7a7264]">Transaksi Saya (Shift Ini)</div>
            <div class="mt-2 font-mono text-2xl font-bold text-[#c9934a]">
                {{ $myTransactions }} <span class="text-xs font-sans font-normal text-[#8a8073]">transaksi</span>
            </div>
        </div>
    </div>


    

    <!-- Grid Bawah: 5 Transaksi Terakhir & Peringatan Stok Menipis -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Transaksi Terakhir -->
        <div class="lg:col-span-7 rounded-xl border border-[#e2dac8] bg-white overflow-hidden">
            <div class="flex items-center justify-between border-b border-[#e2dac8] bg-[#f7f4ee]/60 px-5 py-3.5">
                <h3 class="text-[14px] font-semibold">5 Transaksi Terakhir</h3>
                <a href="{{ route('cashier.histories.index') }}" class="text-xs font-medium text-[#a67b3f] hover:underline">Lihat Semua →</a>
            </div>
            <table class="w-full text-left text-[12.5px]">
                <thead class="border-b border-[#e2dac8] bg-[#f7f4ee] text-[10.5px] uppercase text-[#8a8073]">
                    <tr>
                        <th class="px-5 py-2.5">Invoice</th>
                        <th class="px-3 py-2.5">Waktu</th>
                        <th class="px-3 py-2.5 text-right">Total</th>
                        <th class="px-5 py-2.5 text-right">Nota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2dac8]/70">
                    @forelse ($recentSales as $sale)
                        <tr class="hover:bg-[#f7f4ee]/40">
                            <td class="px-5 py-3 font-mono font-semibold">{{ $sale->invoice_number }}</td>
                            <td class="px-3 py-3 text-[#7a7264]">{{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }}</td>
                            <td class="px-3 py-3 text-right font-mono font-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('cashier.histories.show', $sale->id) }}" class="text-xs font-semibold text-blue-600 hover:underline">Struk</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-[#7a7264]">Belum ada transaksi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Stok Menipis (Info untuk Kasir) -->
        <div class="lg:col-span-5 rounded-xl border border-[#e2dac8] bg-white overflow-hidden">
            <div class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 px-5 py-3.5">
                <h3 class="text-[14px] font-semibold">Peringatan Stok Menipis</h3>
            </div>
            <div class="divide-y divide-[#e2dac8]/70">
                @forelse ($lowStockProducts as $item)
                    <div class="flex items-center justify-between px-5 py-3 text-[12.5px]">
                        <div>
                            <div class="font-medium">{{ $item->name }}</div>
                            <div class="font-mono text-[11px] text-[#8a8073]">{{ $item->sku }}</div>
                        </div>
                        <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">
                            Sisa {{ $item->stock }} {{$item->pcs }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-[12.5px] text-[#7a7264]">Semua stok barang aman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection