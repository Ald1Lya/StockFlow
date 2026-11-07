@extends('layouts.admin-layout')

@section('page-title', 'Brand')

@section('content')
<x-modal
    :show="session()->has('success')"
    title="Berhasil"
    :message="session('success', '')"
    action-text="Oke, mengerti"
/>

<section>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data</div>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Brand Produk</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">{{ $brands->total() }} brand ditemukan</p>
        </div>

        <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
            <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Brand Baru
        </a>
    </div>

    <form method="GET" action="{{ route('admin.brands.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
        <label for="brand-search" class="sr-only">Cari brand berdasarkan nama atau kode</label>
        <div class="relative w-full sm:max-w-sm">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8073]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
            </svg>
            <input
                id="brand-search"
                type="search"
                name="search"
                value="{{ $search }}"
                maxlength="100"
                placeholder="Cari berdasarkan nama atau kode brand..."
                class="w-full rounded-md border border-[#e2dac8] bg-white py-2 pl-9 pr-3 text-[12.5px] text-[#3a352d] placeholder:text-[#a89f8e] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
            >
        </div>
        <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
            Cari
        </button>
        @if ($search !== '')
            <a href="{{ route('admin.brands.index') }}" class="px-2 py-2 text-[12.5px] font-medium text-[#7a7264] hover:text-[#161410]">
                Reset
            </a>
        @endif
    </form>

    @if ($brands->isEmpty())
        <div class="rounded-lg border border-dashed border-[#d6cdb8] bg-white px-4 py-10 text-center">
            <p class="text-sm font-medium text-[#3a352d]">Brand tidak ditemukan</p>
            <p class="mt-1 text-[12.5px] text-[#7a7264]">Coba kata kunci nama atau kode yang berbeda.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]">
                            <th scope="col" class="w-12 px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">No.</th>
                            <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">Kode</th>
                            <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">Nama Brand</th>
                            <th scope="col" class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">Deskripsi</th>
                            <th scope="col" class="px-4 py-2.5 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">Produk</th>
                            <th scope="col" class="px-4 py-2.5 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-[#8a8073]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr class="border-b border-[#f0ebdf] last:border-b-0 hover:bg-[#faf8f3]">
                                <td class="w-12 px-4 py-3 font-mono text-[11.5px] text-[#8a8073]">
                                    {{ ($brands->firstItem() ?? 0) + $loop->index }}
                                </td>
                                <td class="px-4 py-3 font-mono text-[12px] font-semibold text-[#161410]">
                                    {{ $brand->code }}
                                </td>
                                <td class="px-4 py-3 text-[13px] font-medium text-[#161410]">
                                    {{ $brand->name }}
                                </td>
                                <td class="max-w-[260px] px-4 py-3 text-[12.5px] text-[#554e44]">
                                    @if ($brand->description)
                                        <span class="line-clamp-2" title="{{ $brand->description }}">{{ $brand->description }}</span>
                                    @else
                                        <span class="italic text-[#a89f8e]">Tidak ada deskripsi</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-[13px] font-semibold tnum text-[#161410]">
                                    {{ $brand->products_count }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.brands.edit', $brand) }}" class="rounded-md px-3 py-1.5 text-[12.5px] font-medium text-[#a67b3f] hover:bg-[#f7f4ee] hover:text-[#161410]">
                                            Edit
                                        </a>

                                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-red-200 px-3 py-1.5 font-medium text-red-600 transition hover:border-red-300 hover:bg-red-50"
                                                    aria-label="Hapus Brand {{ $brand->name }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-10 0 .7 12h6.6L16 7M9 7V4h6v3m-5 3v6m4-6v6" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">
            {{ $brands->links() }}
        </div>
    @endif
</section>
@endsection