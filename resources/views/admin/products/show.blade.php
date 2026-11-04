@extends('layouts.admin-layout')

@section('page-title', 'Detail Produk')

@section('content')
<section>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Produk</div>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Detail Produk</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">Informasi contoh produk · SKU-SMN-8821</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Kembali</a>
            <a href="{{ route('admin.products.edit', ['id' => 1]) }}" class="rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">Edit Produk</a>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="flex min-h-64 items-center justify-center rounded-lg border border-[#e2dac8] bg-white p-6">
            <div class="flex h-44 w-full items-center justify-center rounded-md bg-[#f7f4ee] text-center text-[12px] text-[#8a8073]">
                <div><div class="mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-[#c9934a]/15 text-xl text-[#a67b3f]">▧</div>Pratinjau foto produk</div>
            </div>
        </div>

        <div class="rounded-lg border border-[#e2dac8] bg-white p-5 lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="rounded bg-[#f7f4ee] px-2 py-1 font-mono text-[10.5px] text-[#8a8073]">SKU-SMN-8821</span>
                    <h2 class="mt-3 text-xl font-semibold text-[#161410]">Semen Portland 50 kg</h2>
                    <p class="mt-1 text-[13px] text-[#7a7264]">Kategori Semen · Brand Tiga Roda</p>
                </div>
                <span class="rounded bg-[#c9934a]/15 px-2 py-1 text-[11px] font-semibold text-[#a67b3f]">Stok rendah</span>
            </div>
            <div class="mt-5 grid gap-4 border-t border-[#e2dac8] pt-4 sm:grid-cols-3">
                <div><div class="text-[10px] font-semibold uppercase tracking-wider text-[#8a8073]">Harga jual</div><div class="mt-1 font-mono text-[15px] font-semibold text-[#161410]">Rp 89.000</div></div>
                <div><div class="text-[10px] font-semibold uppercase tracking-wider text-[#8a8073]">Stok tersedia</div><div class="mt-1 font-mono text-[15px] font-semibold text-[#161410]">15 sak</div></div>
                <div><div class="text-[10px] font-semibold uppercase tracking-wider text-[#8a8073]">Minimum stok</div><div class="mt-1 font-mono text-[15px] font-semibold text-[#161410]">50 sak</div></div>
            </div>
            <div class="mt-5 border-t border-[#e2dac8] pt-4">
                <h3 class="text-[12px] font-semibold text-[#161410]">Deskripsi</h3>
                <p class="mt-1.5 text-[12.5px] leading-5 text-[#7a7264]">Semen Portland untuk kebutuhan konstruksi dan renovasi.</p>
            </div>
        </div>
    </div>
</section>
@endsection