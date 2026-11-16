@extends('layouts.admin-layout')

@section('page-title', 'Laporan Penjualan')

@section('content')
<section x-data="{ openDetailId: null }">
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-[#161410]">Laporan Penjualan</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">Rekap transaksi kasir, omzet, dan estimasi laba kotor</p>
        </div>
    </div>

    <!-- 3 Kartu Ringkasan Keuangan -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-[#e2dac8] bg-white p-5">
            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Total Omzet</div>
            <div class="mt-2 font-mono text-2xl font-bold text-[#161410]">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-lg border border-[#e2dac8] bg-white p-5">
            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Estimasi Laba Kotor</div>
            <div class="mt-2 font-mono text-2xl font-bold text-emerald-700">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-lg border border-[#e2dac8] bg-white p-5">
            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Jumlah Transaksi</div>
            <div class="mt-2 font-mono text-2xl font-bold text-[#c9934a]">
                {{ number_format($totalTransactions, 0, ',', '.') }} <span class="font-sans text-xs font-normal text-[#7a7264]">struk</span>
            </div>
        </div>
    </div>

    <!-- Form Filter -->
    <form action="{{ route('admin.report_sales.index') }}" method="GET" class="mb-4 flex flex-wrap items-center gap-2">
        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari No. Invoice (INV-...)..."
            class="w-full max-w-xs rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] placeholder:text-[#b8ae9b] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
        >

        <select
            name="cashier_id"
            class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none"
        >
            <option value="">Semua Kasir</option>
            @foreach ($cashiers as $cashier)
                <option value="{{ $cashier->id }}" @selected((string) $cashierId === (string) $cashier->id)>
                    {{ $cashier->name }}
                </option>
            @endforeach
        </select>

        <input
            type="date"
            name="start_date"
            value="{{ $startDate }}"
            title="Dari Tanggal"
            class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none"
        >

        <span class="text-xs text-[#8a8073]">s/d</span>

        <input
            type="date"
            name="end_date"
            value="{{ $endDate }}"
            title="Sampai Tanggal"
            class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none"
        >

        <button type="submit" class="rounded-md bg-[#161410] px-3.5 py-1.5 text-[12.5px] font-medium text-white hover:bg-black">
            Filter
        </button>

        @if ($search !== '' || $startDate || $endDate || $cashierId)
            <a href="{{ route('admin.report_sales.index') }}" class="px-2.5 py-1.5 text-[12.5px] text-[#7a7264] hover:text-[#161410]">
                Reset
            </a>
        @endif
    </form>

    <!-- Tabel Laporan Penjualan -->
    <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full table-auto border-collapse text-left text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#e2dac8] bg-[#f7f4ee] text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                        <th class="px-5 py-3 font-semibold">No.</th>
                        <th class="px-4 py-3 font-semibold">No. Invoice</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Kasir</th>
                        <th class="px-4 py-3 text-right font-semibold">Total Belanja</th>
                        <th class="px-4 py-3 text-right font-semibold">Tunai</th>
                        <th class="px-4 py-3 text-right font-semibold">Kembalian</th>
                        <th class="px-5 py-3 text-center font-semibold">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-[#f7f4ee]/50">
                            <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">
                                {{ ($sales->firstItem() ?? 0) + $loop->index }}
                            </td>
                            <td class="px-4 py-3 font-mono text-[11.5px] font-semibold text-[#161410]">
                                {{ $sale->invoice_number }}
                            </td>
                            <td class="px-4 py-3 text-[12px] text-[#7a7264]">
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $sale->user?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-semibold text-[#161410]">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-[#7a7264]">
                                Rp {{ number_format($sale->cash_tendered, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-emerald-700">
                                Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <button
                                    type="button"
                                    @click="openDetailId = (openDetailId === {{ $sale->id }} ? null : {{ $sale->id }})"
                                    class="rounded border border-[#e2dac8] bg-[#f7f4ee] px-2.5 py-1 text-[11px] font-semibold text-[#3a352d] hover:border-[#c9934a]"
                                >
                                    <span x-text="openDetailId === {{ $sale->id }} ? 'Tutup' : '{{ $sale->details->count() }} Item'"></span>
                                </button>
                            </td>
                        </tr>

                        <!-- Baris Expandable Rincian Barang per Invoice -->
                        <tr x-show="openDetailId === {{ $sale->id }}" x-cloak class="bg-[#f7f4ee]/40">
                            <td colspan="8" class="px-6 py-3">
                                <div class="rounded-md border border-[#e2dac8] bg-white p-3">
                                    <div class="mb-2 text-[11px] font-semibold uppercase tracking-wider text-[#8a8073]">
                                        Item Terjual ({{ $sale->invoice_number }})
                                    </div>
                                    <div class="divide-y divide-[#e2dac8]/60 text-[12px]">
                                        @foreach ($sale->details as $detail)
                                            <div class="flex items-center justify-between py-1.5">
                                                <div>
                                                    <span class="font-medium text-[#161410]">{{ $detail->product?->name ?? 'Produk Dihapus' }}</span>
                                                    <span class="ml-1.5 font-mono text-[11px] text-[#8a8073]">({{ $detail->product?->sku ?? '-' }})</span>
                                                </div>
                                                <div class="font-mono text-[12px]">
                                                    {{ $detail->quantity }} {{ $detail->product?->pcs ?? '' }} × Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                                    = <span class="font-semibold text-[#161410]">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-[12.5px] text-[#7a7264]">
                                Belum ada data transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</section>
@endsection