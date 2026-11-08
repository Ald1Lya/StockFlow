@extends('layouts.admin-layout')

@section('page-title', 'Edit Kategori')

@section('content')
<section class="mx-auto max-w-2xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">
            Master Data / Edit Kategori
        </div>

        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">
            Edit Kategori
        </h1>

        <p class="mt-1 text-[13px] text-[#7a7264]">
            Ubah informasi kategori {{ $category->name }}
        </p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-4">

                {{-- Kode (read-only, hanya info) --}}
                <div>
                    <label class="text-[12px] font-medium text-[#3a352d]">
                        Kode Kategori
                    </label>
                    <div class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-[#f7f4ee] px-3 py-2 text-[13px] text-[#7a7264]">
                        {{ $category->code }}
                    </div>
                    <p class="mt-1 text-[11px] text-[#a09880]">
                        Kode akan diperbarui otomatis jika nama kategori diubah.
                    </p>
                </div>

                {{-- Nama --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Nama Kategori

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Masukkan nama kategori"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none @error('name') border-red-400 @enderror"
                    >

                    @error('name')
                        <span class="mt-1 block text-[11px] text-red-500">{{ $message }}</span>
                    @enderror
                </label>

                {{-- Deskripsi --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Deskripsi

                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Masukkan deskripsi kategori (opsional)"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none @error('description') border-red-400 @enderror"
                    >{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <span class="mt-1 block text-[11px] text-red-500">{{ $message }}</span>
                    @enderror
                </label>

            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">

                <a href="{{ route('admin.categories.index') }}"
                    class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                    Batal
                </a>

                <button type="submit"
                    class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">
                    Simpan Perubahan
                </button>

            </div>

        </form>
    </div>
</section>
@endsection
