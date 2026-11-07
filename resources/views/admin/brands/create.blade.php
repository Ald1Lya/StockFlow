@extends('layouts.admin-layout')

@section('page-title', 'Brand Baru')

@section('content')

<section class="mx-auto max-w-2xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Tambah Brand</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Brand Baru</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Tambahkan brand bahan bangunan ke inventaris.</p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <x-alert type="error" class="mb-4">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <div class="grid gap-4">

                <label class="text-[12px] font-medium text-[#3a352d]">
                    Nama brand
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama brand"
                        maxlength="255"
                        required
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >
                </label>

                <p class="text-[11px] text-[#8a8073]">
                    
                </p>
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Deskripsi
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Masukkan deskripsi brand (opsional)"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >{{ old('description') }}</textarea>
                </label>

            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
                <a href="{{ route('admin.brands.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                    Batal
                </a>
                <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">
                    Simpan Brand
                </button>
            </div>
        </form>
    </div>
</section>

@endsection
