@extends('layouts.warehouse-layout')

@section('page-title', 'Stok Produk')

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
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Stok Produk</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">{{ $iventorys->total() }} produk terdaftar di gudang</p>
        </div>
    </div>

    <form action="{{ route('warehouse.iventorys.index') }}" method="GET" class="mb-4 flex flex-wrap items-center gap-2">
        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nama produk, SKU, kategori, atau brand..."
            class="w-full max-w-xs rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] placeholder:text-[#b8ae9b] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
        >

        <select
            name="stock_status"
            onchange="this.form.submit()"
            class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
        >
            <option value="">Semua Kondisi Stok</option>
            <option value="low" @selected($stockFilter === 'low')>Stok Menipis</option>
            <option value="out" @selected($stockFilter === 'out')>Stok Habis</option>
        </select>

        <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
            Cari
        </button>

        @if ($search !== '' || $stockFilter !== '')
            <a href="{{ route('warehouse.iventorys.index') }}" class="rounded-md px-2.5 py-1.5 text-[12.5px] text-[#7a7264] hover:text-[#161410]">
                Reset
            </a>
        @endif
    </form>

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
                        <th class="px-3 py-3 font-semibold">Min. Stok</th>
                        <th class="px-3 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                    @forelse ($iventorys as $product)
                        <tr class="hover:bg-[#f7f4ee]/50">
                            <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">
                                {{ ($iventorys->firstItem() ?? 0) + $loop->index }}
                            </td>
                            <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">
                                {{ $product->sku }}
                            </td>
                            <td class="px-3 py-3 font-medium">
                                {{ $product->name }}
                            </td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">
                                {{ $product->category?->name ?? '-' }}
                            </td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">
                                {{ $product->brand?->name ?? '-' }}
                            </td>
                            <td class="px-3 py-3 font-mono text-[11.5px] text-[#8a8073]">
                                {{ number_format($product->min_stock, 0, ',', '.') }} {{ $product->pcs }}
                            </td>
                            <td class="px-3 py-3">
                                <span class="rounded px-1.5 py-0.5 text-[10.5px] font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                @if ($product->stock <= 0)
                                    <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700">
                                        0 {{ $product->pcs }} · Habis
                                    </span>
                                @elseif ($product->stock <= $product->min_stock)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        {{ number_format($product->stock, 0, ',', '.') }} {{ $product->pcs }} · Menipis
                                    </span>
                                @else
                                    <span class="font-semibold text-[#3a352d]">
                                        {{ number_format($product->stock, 0, ',', '.') }} {{ $product->pcs }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-[12.5px] text-[#7a7264]">
                                {{ ($search !== '' || $stockFilter !== '') ? 'Produk tidak ditemukan.' : 'Belum ada data stok produk.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $iventorys->links() }}
    </div>
</section>
@endsection