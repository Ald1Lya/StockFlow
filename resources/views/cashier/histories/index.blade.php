@extends('layouts.cashier-layout')

@section('page-title', 'Riwayat Belanja')

@section('content')
<header class="bg-white border-b border-[#e2dac8] h-16 flex items-center justify-between px-8 sticky top-0 z-10">
    <h1 class="text-[16px] font-semibold tracking-tight">Riwayat Belanja</h1>
    <div class="text-[12px] text-[#7a7264]">Total {{ $sales->total() }} transaksi</div>
</header>

<div class="p-8 space-y-5">
    <form action="{{ route('cashier.histories.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nomor invoice (INV-...)..."
            class="w-full max-w-xs rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
        >
        <input
            type="date"
            name="date"
            value="{{ $date }}"
            class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] focus:border-[#c9934a] focus:outline-none"
        >
        <button type="submit" class="rounded-md bg-[#161410] px-3.5 py-1.5 text-[12.5px] font-medium text-white hover:bg-black">
            Filter
        </button>
        @if ($search !== '' || $date)
            <a href="{{ route('cashier.histories.index') }}" class="px-2.5 py-1.5 text-[12.5px] text-[#7a7264] hover:text-black">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white border border-[#e2dac8] rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left text-[13px]">
            <thead class="bg-[#f7f4ee] border-b border-[#e2dac8] text-[11px] uppercase tracking-wider text-[#8a8073]">
                <tr>
                    <th class="px-6 py-3.5 font-semibold">No. Invoice</th>
                    <th class="px-4 py-3.5 font-semibold">Tanggal & Jam</th>
                    <th class="px-4 py-3.5 font-semibold">Kasir</th>
                    <th class="px-4 py-3.5 text-right font-semibold">Total Tagihan</th>
                    <th class="px-4 py-3.5 text-right font-semibold">Tunai</th>
                    <th class="px-4 py-3.5 text-right font-semibold">Kembalian</th>
                    <th class="px-6 py-3.5 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e2dac8]">
                @forelse ($sales as $sale)
                    <tr class="hover:bg-[#f7f4ee]/50 transition-colors">
                        <td class="px-6 py-3.5 font-mono font-bold text-[#161410]">{{ $sale->invoice_number }}</td>
                        <td class="px-4 py-3.5 text-[#7a7264]">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3.5 font-medium">{{ $sale->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3.5 font-mono font-bold text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 font-mono text-[#7a7264] text-right">Rp {{ number_format($sale->cash_tendered, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 font-mono text-green-600 font-bold text-right">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <a href="{{ route('cashier.histories.show', $sale->id) }}"
                               class="inline-block rounded-md bg-blue-50 px-3 py-1.5 text-[12px] font-semibold text-blue-600 hover:bg-blue-100">
                                Lihat Nota
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-[#7a7264]">Belum ada riwayat transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $sales->links() }}
    </div>
</div>
@endsection