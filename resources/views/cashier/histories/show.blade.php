@extends('layouts.cashier-layout')

@section('page-title', 'Detail Nota ' . $sale->invoice_number)

@section('content')
<header class="bg-white border-b border-[#e2dac8] h-16 flex items-center justify-between px-8 sticky top-0 z-10 print:hidden">
    <div class="flex items-center gap-3">
        <a href="{{ route('cashier.histories.index') }}" class="text-xs font-medium text-[#7a7264] hover:text-black">
            ← Kembali ke Riwayat
        </a>
        <span class="text-[#e2dac8]">|</span>
        <h1 class="text-[15px] font-semibold">Nota {{ $sale->invoice_number }}</h1>
    </div>
    <button type="button" onclick="window.print()" class="rounded-lg bg-[#161410] px-4 py-2 text-xs font-semibold text-[#c9934a] hover:bg-black">
        Cetak Struk
    </button>
</header>

<div class="p-8 print:p-0">
    <div class="max-w-md bg-white border border-[#e2dac8] p-8 rounded-xl shadow-sm mx-auto font-mono text-[12.5px] text-[#161410] relative overflow-hidden print:border-none print:shadow-none">
        <div class="absolute top-0 left-0 w-full h-1 bg-[#c9934a] print:hidden"></div>

        <div class="text-center border-b-2 border-dashed border-gray-300 pb-5 mb-5 mt-1">
            <h3 class="font-bold text-[18px] tracking-wide mb-1">TOKO BANGUNAN</h3>
            <p class="text-[11.5px] text-[#7a7264]">Nota Penjualan Resmi</p>
        </div>

        <div class="space-y-1.5 mb-5 text-[11.5px]">
            <div class="flex justify-between">
                <span class="text-[#7a7264]">No. Invoice :</span>
                <span class="font-bold">{{ $sale->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-[#7a7264]">Tanggal :</span>
                <span>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-[#7a7264]">Kasir :</span>
                <span>{{ $sale->user?->name ?? '-' }}</span>
            </div>
        </div>

        <div class="border-t border-b border-dashed border-gray-300 py-4 mb-4 space-y-3">
            @foreach ($sale->details as $detail)
                <div>
                    <div class="font-bold text-[12.5px]">{{ $detail->product?->name ?? 'Produk Dihapus' }}</div>
                    <div class="flex justify-between text-[11.5px] mt-0.5">
                        <span class="text-[#7a7264]">{{ $detail->quantity }} {{ $detail->product?->pcs ?? '' }} x {{ number_format($detail->unit_price, 0, ',', '.') }}</span>
                        <span class="font-bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-2">
            <div class="flex justify-between font-bold text-[14px]">
                <span>TOTAL</span>
                <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[#7a7264] mt-2">
                <span>Tunai (Cash)</span>
                <span>Rp {{ number_format($sale->cash_tendered, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-green-600 border-t border-dashed border-gray-300 pt-2 mt-2">
                <span>KEMBALI</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-8 text-center text-[10.5px] text-[#7a7264] border-t border-dashed border-gray-300 pt-4">
            <p>Barang yang sudah dibeli tidak dapat<br>ditukar atau dikembalikan.</p>
            <p class="font-bold mt-2 text-[#161410]">TERIMA KASIH</p>
        </div>
    </div>
</div>
@endsection