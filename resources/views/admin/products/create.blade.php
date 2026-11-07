@extends('layouts.admin-layout')

@section('page-title', 'Produk Baru')


@section('content')
<section class="mx-auto max-w-4xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Produk</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Produk Baru</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Isi detail produk untuk menambahkannya ke data inventaris.</p>
    </div>
    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">

     <!-- 1. TAMBAHKAN: method="POST" dan enctype="multipart/form-data" (untuk upload foto nanti) -->
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($errors->any())
        <x-alert type="error" class="mb-4">
            <p class="font-semibold">Produk belum tersimpan. Periksa kembali isian berikut:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <!-- Input Nama Produk -->
        <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Nama produk
            <input name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan nama produk" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <div class="relative" data-search-select data-search-url="{{ route('admin.products.create') }}" data-search-type="brand" data-search-label="brand" data-search-allow-empty="true">
            <label for="brand-search" class="text-[12px] font-medium text-[#3a352d]">Brand <span class="font-normal text-[#7a7264]">(opsional)</span></label>
            <div class="relative mt-1.5">
                <input id="brand-search" type="search" value="{{ $selectedBrand?->name }}" placeholder="Cari nama brand..." autocomplete="off" autocapitalize="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="brand-options" data-search-select-input class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                <input type="hidden" name="brand_id" value="{{ old('brand_id') }}" data-search-select-value>
                <div id="brand-options" role="listbox" aria-label="Hasil pencarian brand" data-search-select-results class="absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg"></div>
            </div>
            <p data-search-select-status role="status" aria-live="polite" class="mt-1 min-h-4 text-[11px] text-[#7a7264]">Ketik nama brand untuk mencari, atau kosongkan jika tanpa brand.</p>
        </div>

        <div class="relative" data-search-select data-search-url="{{ route('admin.products.create') }}" data-search-type="category" data-search-label="kategori">
            <label for="category-search" class="text-[12px] font-medium text-[#3a352d]">Kategori</label>
            <div class="relative mt-1.5">
                <input id="category-search" type="search" value="{{ $selectedCategory?->name }}" placeholder="Cari nama kategori..." autocomplete="off" autocapitalize="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="category-options" data-search-select-input required class="w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                <input type="hidden" name="category_id" value="{{ old('category_id') }}" data-search-select-value>
                <div id="category-options" role="listbox" aria-label="Hasil pencarian kategori" data-search-select-results class="absolute z-20 mt-1 hidden max-h-56 w-full overflow-y-auto rounded-md border border-[#e2dac8] bg-white py-1 shadow-lg"></div>
            </div>
            <p data-search-select-status role="status" aria-live="polite" class="mt-1 min-h-4 text-[11px] text-[#7a7264]">Ketik untuk mencari lalu pilih kategori.</p>
        </div>

        <!-- Input Harga Modal -->
        <label class="text-[12px] font-medium text-[#3a352d]">Harga modal
            <input name="cost_price" type="number"   value="{{ old('cost_price') }}" placeholder="Masukkan harga modal" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Harga Jual (Sudah dihapus salah satu yang duplikat) -->
        <label class="text-[12px] font-medium text-[#3a352d]">Harga jual
            <input name="selling_price" type="number"   value="{{ old('selling_price') }}" placeholder="Masukkan harga jual" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Stok Awal -->
        <label class="text-[12px] font-medium text-[#3a352d]">Stok awal
            <input name="stock" type="number"   value="{{ old('stock') }}" placeholder="Masukkan jumlah stok" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Batas Minimum Stok -->
        <label class="text-[12px] font-medium text-[#3a352d]">Batas minimum stok
            <input name="min_stock" type="number"   value="{{ old('min_stock') }}" placeholder="Masukkan batas minimum stok" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Deskripsi -->
        <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Deskripsi
            <textarea name="description" rows="4" placeholder="Masukkan deskripsi produk" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">{{ old('description') }}</textarea>
        </label>
        
        <!-- Input Tambahan: Upload Gambar (Opsional jika mau dipakai sekarang) -->
        <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Gambar Produk
            <input name="image" type="file" accept="image/*" class="mt-1.5 w-full text-[13px]">
        </label>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
        <a href="{{ route('admin.products.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Batal</a>
        <!-- 3. UBAH: type="button" menjadi type="submit" agar form bisa terkirim -->
        <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">Simpan Produk</button>
    </div>
</form>


    </div>
</section>
@endsection