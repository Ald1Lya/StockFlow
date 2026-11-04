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
            Form Edit Kategori
        </p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-4">

                <label class="text-[12px] font-medium text-[#3a352d]">
                    Nama kategori

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Contoh: Semen"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >
                </label>

                <label class="text-[12px] font-medium text-[#3a352d]">
                    Kode kategori

                    <input
                        type="text"
                        value="{{ $category->code }}"
                        readonly
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-[#f7f4ee] px-3 py-2 text-[13px]"
                    >
                </label>

                {{-- <label class="text-[12px] font-medium text-[#3a352d]">
                    Deskripsi

                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Keterangan kategori"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >{{ old('description', $category->description) }}</textarea>
                </label> --}}

            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">

                <a href="{{ route('admin.categories.index') }}"
                    class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                    Batal
                </a>

                <button type="submit"
                    class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">
                    Update Kategori
                </button>

            </div>

        </form>
                                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                                method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="ml-3 font-medium text-red-500 hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
    </div>
</section>
@endsection