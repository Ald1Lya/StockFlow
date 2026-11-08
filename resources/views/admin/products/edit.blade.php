@extends('layouts.admin-layout')

@section('page-title', 'Edit Produk')

@section('content')
<section class="mx-auto max-w-4xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Produk</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Edit Produk</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Perbarui informasi produk. SKU akan dibuat ulang jika kategori atau brand diganti.</p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <x-alert type="error" class="mb-4">
                    <p class="font-semibold">Produk belum diperbarui. Periksa kembali isian berikut:</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Nama produk
                    <input name="name" type="text" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">SKU
                    <input type="text" value="{{ $product->sku }}" readonly class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-[#f7f4ee] px-3 py-2 font-mono text-[13px] text-[#7a7264]">
                    <span class="mt-1 block text-[11px] font-normal text-[#7a7264]">SKU berubah otomatis jika kategori atau brand diganti.</span>
                </label>

                <div class="relative" data-search-select data-search-url="{{ route('admin.products.create') }}" data-search-type="brand" data-search-label="brand" data-search-allow-empty="true">
                    <label for="brand-search" class="text-[12px] font-medium text-[#3a352d]">Brand <span class="font-normal text-[#7a7264]">(opsional)</span></label>
                    <div class="relative mt-1.5">
                        <input id="brand-search" name="brand_search" type="search" value="{{ old('brand_search', $product->brand?->name) }}" placeholder="Cari nama brand..." autocomplete="off" autocapitalize="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="brand-options" data-search-select-input class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                        <input type="hidden" name="brand_id" value="{{ old('brand_id', $product->brand_id) }}" data-search-select-value>
                        <div id="brand-options" role="listbox" aria-label="Hasil pencarian brand" data-search-select-results class="absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg"></div>
                    </div>
                    <p data-search-select-status role="status" aria-live="polite" class="mt-1 min-h-4 text-[11px] text-[#7a7264]">Ketik nama brand untuk mencari, atau kosongkan jika tanpa brand.</p>
                </div>

                <div class="relative" data-search-select data-search-url="{{ route('admin.products.create') }}" data-search-type="category" data-search-label="kategori">
                    <label for="category-search" class="text-[12px] font-medium text-[#3a352d]">Kategori</label>
                    <div class="relative mt-1.5">
                        <input id="category-search" name="category_search" type="search" value="{{ old('category_search', $product->category->name) }}" placeholder="Cari nama kategori..." autocomplete="off" autocapitalize="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="category-options" data-search-select-input required class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                        <input type="hidden" name="category_id" value="{{ old('category_id', $product->category_id) }}" data-search-select-value>
                        <div id="category-options" role="listbox" aria-label="Hasil pencarian kategori" data-search-select-results class="absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg"></div>
                    </div>
                    <p data-search-select-status role="status" aria-live="polite" class="mt-1 min-h-4 text-[11px] text-[#7a7264]">Ketik untuk mencari lalu pilih kategori.</p>
                </div>

                <label class="text-[12px] font-medium text-[#3a352d]">Harga modal
                    <input name="cost_price" type="number" min="0" value="{{ old('cost_price', $product->cost_price) }}" placeholder="Masukkan harga modal" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">Harga jual
                    <input name="selling_price" type="number" min="0" value="{{ old('selling_price', $product->selling_price) }}" placeholder="Masukkan harga jual" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">Stok saat ini
                    <input name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" placeholder="Masukkan jumlah stok" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">Batas minimum stok
                    <input name="min_stock" type="number" min="0" value="{{ old('min_stock', $product->min_stock) }}" placeholder="Masukkan batas minimum stok" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>

                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Deskripsi
                    <textarea name="description" rows="4" placeholder="Masukkan deskripsi produk" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">{{ old('description', $product->description) }}</textarea>
                </label>

                @if ($product->image)
                    <div class="sm:col-span-2">
                        <p class="text-[12px] font-medium text-[#3a352d]">Gambar saat ini</p>
                        <img src="{{ asset('storage/'.$product->image) }}" alt="Gambar {{ $product->name }}" class="mt-1.5 h-32 w-32 rounded-md border border-[#e2dac8] object-cover">
                    </div>
                @endif

                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Ganti gambar produk <span class="font-normal text-[#7a7264]">(opsional, maksimal 2 MB)</span>
                    <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 w-full text-[13px]">
                </label>

                <label class="flex items-start gap-2 text-[12px] font-medium text-[#3a352d] sm:col-span-2">
                    <input name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->is_active)) class="mt-0.5 rounded border-[#e2dac8] text-[#161410] focus:ring-[#c9934a]">
                    <span>
                        Produk aktif
                        <span class="mt-0.5 block font-normal text-[#7a7264]">Status aktif menandakan produk masih dijual; stok habis tidak otomatis menonaktifkannya.</span>
                    </span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
                <a href="{{ route('admin.products.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Batal</a>
                <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</section>
@endsection
