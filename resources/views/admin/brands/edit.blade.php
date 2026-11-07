@extends('layouts.admin-layout')

@section('page-title', 'Edit Brand')

@section('content')
<section class="mx-auto max-w-2xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Edit Brand</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Edit Brand</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Perbarui informasi brand produk.</p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST">
            @csrf
            @method('PUT')

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
                        value="{{ old('name', $brand->name) }}"
                        maxlength="255"
                        required
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >
                </label>

                <p class="text-[11px] text-[#8a8073]">
                    Kode akan dibuat ulang dari kategori dan inisial nama brand saat disimpan.
                </p>

                <label class="text-[12px] font-medium text-[#3a352d]">
                    Kode brand
                    <input
                        type="text"
                        value="{{ $brand->code }}"
                        readonly
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-[#f7f4ee] px-3 py-2 text-[13px]"
                    >
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">
                    Deskripsi
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Masukkan deskripsi brand (opsional)"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >{{ old('description', $brand->description) }}</textarea>
                </label>
            </div>

            <div class="mt-6 flex flex-wrap justify-between gap-2 border-t border-[#e2dac8] pt-4">


                <div class="flex gap-2">
                    <a href="{{ route('admin.brands.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">
                        Update Brand
                    </button>
                </div>
            </div>
        </form>

    </div>
</section>
@endsection
