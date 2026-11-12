@extends('layouts.warehouse-layout')



@section('content')
<x-modal
    :show="session()->has('success')"
    title="Berhasil"
    :message="session('success', '')"
    action-text="Oke, mengerti"
/>

<section class="mx-auto max-w-3xl">

    {{-- Header --}}
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">
            Inventaris / Pengajuan Stok
        </div>
       

    </div>

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-[12.5px] text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Kartu Info Produk --}}
    <div class="mb-5 overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
        <div class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 px-5 py-3">
            <h2 class="text-[12px] font-semibold uppercase tracking-wider text-[#8a8073]">
                Informasi Produk
            </h2>
        </div>

        <dl class="grid grid-cols-1 gap-x-6 gap-y-3 px-5 py-4 text-[12.5px] sm:grid-cols-2">
            <div>
                <dt class="text-[10.5px] uppercase tracking-wider text-[#8a8073]">Kode Produk</dt>
                <dd class="mt-0.5 font-mono text-[#3a352d]">{{ $product->sku ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-[10.5px] uppercase tracking-wider text-[#8a8073]">Nama Produk</dt>
                <dd class="mt-0.5 font-medium text-[#161410]">{{ $product->name ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-[10.5px] uppercase tracking-wider text-[#8a8073]">Kategori</dt>
                <dd class="mt-0.5 text-[#3a352d]">{{ $product->category?->name ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-[10.5px] uppercase tracking-wider text-[#8a8073]">Merek</dt>
                <dd class="mt-0.5 text-[#3a352d]">{{ $product->brand?->name ?? '-' }}</dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-[10.5px] uppercase tracking-wider text-[#8a8073]">Stok Saat Ini</dt>
                <dd class="mt-0.5 font-mono text-lg font-semibold tabular-nums text-[#161410]">
                    {{ number_format($product->stock ?? 0, 0, ',', '.') }}
                    <span class="text-[11px] font-normal text-[#8a8073]">unit</span>
                </dd>
            </div>
        </dl>
    </div>

    {{-- Form Pengajuan --}}
<form method="POST" action="{{ route('warehouse.stockmovements.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- Kategori Kondisi Barang --}}
    <div>
        <label for="condition" class="mb-1 block text-[12.5px] font-medium text-[#3a352d]">Kondisi Barang di Gudang</label>
        <select id="condition" name="condition" required
                class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none">
            <option value="Barang Rusak / Cacat" {{ old('condition') === 'Barang Rusak / Cacat' ? 'selected' : '' }}>Barang Rusak / Cacat Fisik</option>
            <option value="Kadaluarsa (Expired)" {{ old('condition') === 'Kadaluarsa (Expired)' ? 'selected' : '' }}>Kadaluarsa (Expired)</option>
            <option value="Kemasan Rusak" {{ old('condition') === 'Kemasan Rusak' ? 'selected' : '' }}>Kemasan Rusak / Tidak Layak Jual</option>
            <option value="Hilang / Selisih Opname" {{ old('condition') === 'Hilang / Selisih Opname' ? 'selected' : '' }}>Hilang / Selisih Stok Opname</option>
            <option value="Lainnya" {{ old('condition') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @error('condition')
            <p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Jumlah Barang Bermasalah --}}
    <div>
        <label for="quantity" class="mb-1 block text-[12.5px] font-medium text-[#3a352d]">
            Jumlah Barang (Stok tersedia: <strong>{{ $product->stock }}</strong>)
        </label>
        <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required
               class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none">
        @error('quantity')
            <p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Detail Keterangan untuk Dicek Admin --}}
    <div>
        <label for="reason" class="mb-1 block text-[12.5px] font-medium text-[#3a352d]">Detail Keterangan</label>
        <textarea id="reason" name="reason" rows="3" required placeholder="Jelaskan detail kondisi barang untuk dicek Admin..."
                  class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] text-[#3a352d] focus:border-[#c9934a] focus:outline-none">{{ old('reason') }}</textarea>
        @error('reason')
            <p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('warehouse.stockmovements.index') }}" class="rounded-md border border-[#e2dac8] bg-white px-4 py-2 text-[12.5px] font-medium text-[#7a7264] hover:text-[#161410]">
            Batal
        </a>
        <button type="submit" class="rounded-md bg-[#c9934a] px-4 py-2 text-[12.5px] font-medium text-white hover:bg-[#b5813b]">
            Kirim Laporan ke Admin
        </button>
    </div>
</form>
</section>
@endsection