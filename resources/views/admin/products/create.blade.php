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
            <input name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Semen Portland 50 kg" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <label class="text-[12px] font-medium text-[#3a352d]">Brand
            <select name="brand_id" class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                <option value="">Tanpa brand</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </label>

        <!-- Input Kategori (Ubah name dan value option-nya sesuai ID database) -->
        <label class="text-[12px] font-medium text-[#3a352d]">Kategori
            <select name="category_id" class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label>

        <!-- Input Harga Modal -->
        <label class="text-[12px] font-medium text-[#3a352d]">Harga modal
            <input name="cost_price" type="number" min="0" value="{{ old('cost_price', 0) }}" placeholder="0" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Harga Jual (Sudah dihapus salah satu yang duplikat) -->
        <label class="text-[12px] font-medium text-[#3a352d]">Harga jual
            <input name="selling_price" type="number" min="0" value="{{ old('selling_price', 0) }}" placeholder="0" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Stok Awal -->
        <label class="text-[12px] font-medium text-[#3a352d]">Stok awal
            <input name="stock" type="number" min="0" value="{{ old('stock', 0) }}" placeholder="0" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Batas Minimum Stok -->
        <label class="text-[12px] font-medium text-[#3a352d]">Batas minimum stok
            <input name="min_stock" type="number" min="0" value="{{ old('min_stock', 0) }}" placeholder="0" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none" required>
        </label>

        <!-- Input Deskripsi -->
        <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Deskripsi
            <textarea name="description" rows="4" placeholder="Deskripsi produk" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">{{ old('description') }}</textarea>
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