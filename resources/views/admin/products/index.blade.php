@extends('layouts.admin-layout')

@section('page-title', 'Produk')

@section('content')
<section>
    @if (session('success'))
        <x-alert type="success" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data</div>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Produk</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">{{ $products->total() }} produk tersimpan</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
            <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Produk Baru
        </a>
    </div>

    <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4 flex flex-wrap items-center gap-2">
        <input
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Cari berdasarkan nama produk atau SKU..."
            class="w-full max-w-xs rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] placeholder:text-[#b8ae9b] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
        >
        <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-1.5 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
            Cari
        </button>
    </form>

    <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[12.5px]">
                <thead>
                    <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                        <th class="px-5 py-3 font-semibold">No.</th>
                        <th class="px-5 py-3 font-semibold">SKU</th>
                        <th class="px-3 py-3 font-semibold">Nama Produk</th>
                        <th class="px-3 py-3 font-semibold">Kategori</th>
                        <th class="px-3 py-3 font-semibold">Brand</th>
                        <th class="px-3 py-3 font-semibold">Stok</th>
                        <th class="px-3 py-3 font-semibold">Harga Jual</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                    @forelse ($products as $product)
                        <tr class="hover:bg-[#f7f4ee]/50">
                            <td class="px-5 py-3 font-mono text-[11.5px] text-[#8a8073]">{{ ($products->firstItem() ?? 0) + $loop->index }}</td>
                            <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">{{ $product->sku }}</td>
                            <td class="px-3 py-3 font-medium">{{ $product->name }}</td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">{{ $product->category->name }}</td>
                            <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">{{ $product->brand?->name ?? '-' }}</td>
                            <td class="px-3 py-3 font-mono tnum">{{ $product->stock }} <span class="text-[10px] text-[#a89f8e]">/ min {{ $product->min_stock }}</span></td>
                            <td class="px-3 py-3 font-mono tnum">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded px-1.5 py-0.5 text-[10.5px] font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-[12.5px] text-[#7a7264]">
                                {{ $search !== '' ? 'Produk tidak ditemukan.' : 'Belum ada produk. Tambahkan produk pertama Anda.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="border-t border-[#e2dac8] px-5 py-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
