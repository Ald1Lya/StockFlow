@extends('layouts.admin-layout')

@section('page-title', 'Tambah Pengguna')

@section('content')
<section class="mx-auto max-w-3xl">
    <div class="mb-5">
        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Master Data / Pengguna</div>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Tambah Pengguna</h1>
        <p class="mt-1 text-[13px] text-[#7a7264]">Tambahkan akun admin, staff gudang, atau kasir.</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="rounded-lg border border-[#e2dac8] bg-white p-5 sm:p-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-[12px] font-medium text-[#3a352d]">Nama lengkap
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rina Suryani" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                    @error('name') <span class="mt-1 block text-[11px] text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="text-[12px] font-medium text-[#3a352d]">Email
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@stockflow.id" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                    @error('email') <span class="mt-1 block text-[11px] text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="text-[12px] font-medium text-[#3a352d]">Peran
                    <select name="role" required class="mt-1.5 w-full rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                        <option value="warehouse" @selected(old('role') === 'warehouse')>Staff Gudang</option>
                        <option value="cashier" @selected(old('role', 'cashier') === 'cashier')>Kasir</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    </select>
                    @error('role') <span class="mt-1 block text-[11px] text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Kata sandi sementara
                    <input type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                    @error('password') <span class="mt-1 block text-[11px] text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="text-[12px] font-medium text-[#3a352d] sm:col-span-2">Konfirmasi kata sandi
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi" class="mt-1.5 w-full rounded-md border border-[#e2dac8] px-3 py-2 text-[13px] focus:border-[#c9934a] focus:outline-none">
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-[#e2dac8] pt-4">
                <a href="{{ route('admin.users.index') }}" class="rounded-md border border-[#e2dac8] px-4 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">Batal</a>
                <button type="submit" class="rounded-md bg-[#161410] px-4 py-2 text-[12.5px] font-semibold text-[#ece7dd]">Simpan Pengguna</button>
            </div>
        </div>
    </form>
</section>
@endsection