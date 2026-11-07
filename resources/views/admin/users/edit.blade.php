
@extends('layouts.admin-layout')

@section('page-title', 'Edit Pengguna')

@section('content')
<section class="mx-auto max-w-3xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">
            Master Data / Pengguna
        </div>

        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">
            Edit Pengguna
        </h1>

        <p class="mt-1 text-[13px] text-[#7a7264]">
            Perbarui informasi akun pengguna.
        </p>
    </div>

    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">

                {{-- Nama --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Nama lengkap

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
                    >

                    @error('name')
                        <span class="mt-1 block text-[11px] text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </label>

                {{-- Email --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Email

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none focus:ring-2 focus:ring-[#c9934a]/15"
                    >

                    @error('email')
                        <span class="mt-1 block text-[11px] text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </label>

                {{-- Role --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Peran

                    <select
                        name="role"
                        required
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >
                        <option value="cashier" @selected(old('role', $user->role) === 'cashier')>
                            Kasir
                        </option>

                        <option value="warehouse" @selected(old('role', $user->role) === 'warehouse')>
                            Staff Gudang
                        </option>

                        <option value="admin" @selected(old('role', $user->role) === 'admin')>
                            Admin
                        </option>
                    </select>

                    @error('role')
                        <span class="mt-1 block text-[11px] text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </label>

                {{-- Password --}}
                <label class="text-[12px] font-medium text-[#3a352d]">
                    Password baru
                    <span class="font-normal text-[#a89f8e]">(Opsional)</span>

                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Kosongkan jika kata sandi tidak diubah"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >

                    @error('password')
                        <span class="mt-1 block text-[11px] text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </label>

                {{-- Konfirmasi Password --}}
                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">
                    Konfirmasi password baru

                    <input
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Masukkan kembali kata sandi baru"
                        class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none"
                    >
                </label>

            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]"
                >
                    Simpan Perubahan
                </button>

            </div>
        </form>
    </div>
</section>
@endsection
