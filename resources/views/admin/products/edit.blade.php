@extends('layouts.admin-layout')

@section('page-title', 'Edit Produk')

@section('content')
<section class="mx-auto max-w-4xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Produk</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Edit Produk</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Contoh data untuk rancangan form. Perubahan belum disimpan.</p>
    </div>
    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Nama produk
                <input type="text" value="Semen Portland 50 kg" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d]">SKU
                <input type="text" value="SKU-SMN-8821" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d]">Kategori
                <select class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                    <option>Semen</option><option>Besi &amp; Baja</option><option>Cat</option><option>Atap</option>
                </select>
            </label>
            <label class="text-[12px] font-medium text-[#3a352d]">Harga jual
                <input type="number" value="89000" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d]">Stok saat ini
                <input type="number" value="15" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
            </label>
            <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Deskripsi
                <textarea rows="4" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">Semen Portland untuk kebutuhan konstruksi dan renovasi.</textarea>
            </label>
        </div>
        <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
            <a href="{{ route('admin.products.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Batal</a>
            <button type="button" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">Simpan Tampilan</button>
        </div>
    </div>
</section>
@endsection