@extends('layouts.admin-layout')

@section('page-title', 'Supplier')


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
                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data</div>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Supplier</h1>
                        <p class="mt-1 text-[13px] text-[#7a7264]">6 supplier aktif</p>
                    </div>
                    <a href="{{ route('admin.suppliers.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                        <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Supplier Baru
                    </a>
                </div>

                
                <form method="GET" action="{{ route('admin.suppliers.index') }}" class="mb-4 flex flex-wrap items-center gap-2">
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
                        <a href="{{ route('admin.suppliers.index') }}" class="px-2 py-2 text-[12.5px] font-medium text-[#7a7264] hover:text-[#161410]">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[12.5px]">
                          <thead>
                            <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                                <th class="px-5 py-3 font-semibold">Kode</th>
                                <th class="px-3 py-3 font-semibold">Nama Supplier</th>
                                <th class="px-3 py-3 font-semibold">Kontak</th>
                                <th class="px-3 py-3 font-semibold">Alamat</th>
                                <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">

                            @forelse ($suppliers as $supplier)

                                <tr class="hover:bg-[#f7f4ee]/50">

                                    <td class="px-5 py-3 font-mono text-[11.5px] text-[#161410]">
                                        {{ $supplier->code }}
                                    </td>

                                    <td class="px-3 py-3 font-medium">
                                        {{ $supplier->name }}
                                    </td>

                                    <td class="px-3 py-3 text-[11.5px] text-[#7a7264]">
                                        {{ $supplier->phone }}
                                    </td>

                                    <td class="px-3 py-3 text-[11.5px]">
                                        {{ $supplier->address }}
                                    </td>

                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                                class="rounded-md border border-[#e2dac8] px-3 py-1.5 font-medium text-[#a67b3f] transition hover:border-[#c9934a] hover:bg-[#f7f4ee]">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-red-200 px-3 py-1.5 font-medium text-red-600 transition hover:border-red-300 hover:bg-red-50"
                                                    aria-label="Hapus supplier {{ $supplier->name }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-10 0 .7 12h6.6L16 7M9 7V4h6v3m-5 3v6m4-6v6" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center">
                                        <p class="text-sm font-medium text-[#3a352d]">
                                            Supplier tidak ditemukan
                                        </p>
                                        <p class="mt-1 text-[12.5px] text-[#7a7264]">
                                            Coba kata kunci nama atau kode yang berbeda.
                                        </p>
                                    </td>
                                </tr>
                    
                            @endforelse

                        </tbody>
                        
                        </table>
                        <div class="mt-4 flex flex-col gap-3 border-t border-[#e2dac8] pt-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-[11.5px] text-[#7a7264]">
                            Menampilkan {{ $suppliers->firstItem() ?? 0 }}–{{ $suppliers->lastItem() ?? 0 }} dari {{ $suppliers->total() }} pengguna
                            </p>
                             {{ $suppliers->links() }}
                        </div>
     

                    </div>
                </div>
            </section>
@endsection
