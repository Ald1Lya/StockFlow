@extends('layouts.admin-layout')

@section('page-title', 'Kategori Produk')

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
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Kategori Produk</h1>
            <p class="mt-1 text-[13px] text-[#7a7264]">
                {{ $categories->total() }} kategori ditemukan
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
            <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Kategori Baru
        </a>
    </div>

    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
        <label for="category-search" class="sr-only">Cari kategori berdasarkan nama atau kode</label>
        <div class="relative w-full sm:max-w-sm">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8073]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6Z"/>
            </svg>
            <input
                id="category-search"
                type="search"
                name="search"
                value="{{ $search }}"
                maxlength="100"
                placeholder="Cari nama atau kode kategori..."
                class="w-full rounded-md border border-[#e2dac8] bg-white py-2 pl-9 pr-3 text-[12.5px] text-[#3a352d] placeholder:text-[#a89f8e] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
            >
        </div>
        <button type="submit" class="rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
            Cari
        </button>
        @if ($search !== '')
            <a href="{{ route('admin.categories.index') }}" class="px-2 py-2 text-[12.5px] font-medium text-[#7a7264] hover:text-[#161410]">
                Reset
            </a>
        @endif
    </form>

    @if ($categories->isEmpty())
        <div class="rounded-lg border border-dashed border-[#d6cdb8] bg-white px-4 py-10 text-center">
            <p class="text-sm font-medium text-[#3a352d]">Kategori tidak ditemukan</p>
            <p class="mt-1 text-[12.5px] text-[#7a7264]">Coba kata kunci nama atau kode yang berbeda.</p>
        </div>
    @else
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($categories as $category)
                <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}"
                class="rounded-lg border border-[#e2dac8] bg-white p-4 transition hover:border-[#c9934a]">

                    <div class="flex items-center justify-between">
                        <span class="text-[11.5px] uppercase tracking-wider text-[#8a8073]">
                            {{ $category->name }}
                        </span>

                        <span class="font-mono text-[10px] text-[#a89f8e]">
                            {{ $category->code }}
                        </span>
                    </div>

                    <div class="mt-2 font-mono text-xl font-semibold tnum text-[#161410]">
                        {{ $category->products_count }}
                    </div>

                    <div class="mt-0.5 text-[11px] text-[#a89f8e]">
                        produk
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $categories->links() }}
        </div>
    @endif
</section>
@endsection
