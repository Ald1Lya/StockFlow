@extends('layouts.admin-layout')

@section('page-title', 'Purchase Order')

@section('content')
<section>

<x-modal
    :show="session()->has('success')"
    title="Berhasil"
    :message="session('success', '')"
    action-text="Oke, mengerti"
/>

    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Pembelian</div>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Purchase Order</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">{{ $purchases->total() }} PO tersimpan</p>
        </div>

        <a href="{{ route('admin.purchases.create') }}"
            class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
            <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            PO Baru
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.purchases.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
        <div class="relative w-full sm:max-w-sm">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8073]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
            </svg>
            <input
                type="search"
                name="search"
                value="{{ $search }}"
                maxlength="100"
                placeholder="Cari no. PO atau nama supplier..."
                class="w-full rounded-md border border-[#e2dac8] bg-white py-2 pl-9 pr-3 text-[12.5px] text-[#3a352d] placeholder:text-[#a89f8e] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
            >
        </div>
        <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
            Cari
        </button>
        @if ($search !== '')
            <a href="{{ route('admin.purchases.index') }}" class="px-2 py-2 text-[12.5px] font-medium text-[#7a7264] hover:text-[#161410]">
                Reset
            </a>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full table-auto border-collapse text-left text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#e2dac8] bg-[#f7f4ee] text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                        <th class="px-5 py-3 font-semibold">No.</th>
                        <th class="px-3 py-3 font-semibold">No. PO</th>
                        <th class="px-3 py-3 font-semibold">Supplier</th>
                        <th class="px-3 py-3 font-semibold">Dibuat Oleh</th>
                        <th class="px-3 py-3 font-semibold">Tanggal</th>
                        <th class="px-3 py-3 font-semibold">Total</th>
                        <th class="px-3 py-3 font-semibold">Qty Dipesan</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">

                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-[#f7f4ee]/50">
                            <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">
                                {{ ($purchases->firstItem() ?? 0) + $loop->index }}
                            </td>
                            <td class="px-3 py-3 font-mono text-[11.5px] font-medium text-[#161410]">
                                {{ $purchase->po_number }}
                            </td>
                            <td class="px-3 py-3 font-medium">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">
                                {{ $purchase->user->name }}
                            </td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">
                                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-3 py-3 font-mono text-[11.5px]">
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-[11.5px]">
                                @if ($purchase->status === 'received')
                                    <span class="text-[#a09880]">—</span>
                                @else
                                    @php $qtyDipesan = $purchase->details->sum('quantity'); @endphp

                                    @if ($purchase->status === 'cancelled')
                                        <span class="text-[#a09880]">
                                            {{ number_format($qtyDipesan, 0, ',', '.') }} unit
                                        </span>
                                    @else
                                        {{-- Berlaku untuk pending & approved --}}
                                        <span class="inline-flex items-center gap-1 font-semibold text-amber-700">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                                            </svg>
                                            {{ number_format($qtyDipesan, 0, ',', '.') }} unit
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                @php
                                    $statusStyle = match($purchase->status) {
                                        'pending'   => 'bg-yellow-100 text-yellow-700',
                                        'approved'  => 'bg-blue-100 text-blue-700',
                                        'received'  => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-600',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                    $statusLabel = match($purchase->status) {
                                        'pending'   => 'Pending',
                                        'approved'  => 'Disetujui',
                                        'received'  => 'Diterima',
                                        'cancelled' => 'Dibatalkan',
                                        default     => $purchase->status,
                                    };
                                @endphp
                                <span class="rounded px-1.5 py-0.5 text-[10.5px] font-semibold {{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            {{-- <td class="px-5 py-3 text-right">
                                <div class="inline-flex items-center justify-end gap-2"> --}}
                                    {{-- Dropdown update status --}}
                                    {{-- <form action="{{ route('admin.purchases.update', $purchase) }}" method="POST" class="inline-flex items-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()"
                                            class="rounded-md border border-[#e2dac8] px-2 py-1.5 text-[11.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none">
                                            <option value="pending"   {{ $purchase->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                            <option value="approved"  {{ $purchase->status === 'approved'  ? 'selected' : '' }}>Disetujui</option>
                                            <option value="received"  {{ $purchase->status === 'received'  ? 'selected' : '' }}>Diterima</option>
                                            <option value="cancelled" {{ $purchase->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form> --}}
                                {{-- </div>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center">
                                <p class="text-sm font-medium text-[#3a352d]">Belum ada purchase order</p>
                                <p class="mt-1 text-[12.5px] text-[#7a7264]">
                                    {{ $search !== '' ? 'Coba kata kunci yang berbeda.' : 'Tambahkan PO pertama dengan tombol di atas.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($purchases->hasPages())
            <div class="flex flex-col gap-3 border-t border-[#e2dac8] px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-[11.5px] text-[#7a7264]">
                    Menampilkan {{ $purchases->firstItem() }}–{{ $purchases->lastItem() }} dari {{ $purchases->total() }} PO
                </p>
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</section>
@endsection
