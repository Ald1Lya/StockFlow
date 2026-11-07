@extends('layouts.auth-layout')

@section('title', 'Daftar Admin · StockFlow')

@section('content')
    <div class="mb-8">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-[#a67b3f]">Admin pertama</p>
        <h2 class="mt-2.5 text-[28px] leading-tight tracking-tight text-[#161410]"
            style="font-family:'Fraunces', ui-serif, Georgia, serif;">
            Buat akun administrator
        </h2>
        <p class="mt-2 text-sm text-[#7a7264]">
            Pendaftaran ini hanya tersedia sebelum akun pertama dibuat.
        </p>
    </div>

    <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="mb-1.5 block text-[13px] font-medium text-[#3a352d]">Nama lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                   placeholder="Masukkan nama lengkap"
                   class="block w-full rounded-lg border border-[#e2dac8] bg-white px-3.5 py-2.5 text-sm text-[#161410] placeholder:text-[#b8ae9b] transition focus:border-[#c9934a] focus:outline-none focus:ring-4 focus:ring-[#c9934a]/12">
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-[13px] font-medium text-[#3a352d]">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   placeholder="Masukkan alamat email"
                   class="block w-full rounded-lg border border-[#e2dac8] bg-white px-3.5 py-2.5 text-sm text-[#161410] placeholder:text-[#b8ae9b] transition focus:border-[#c9934a] focus:outline-none focus:ring-4 focus:ring-[#c9934a]/12">
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-[13px] font-medium text-[#3a352d]">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password" required placeholder="Masukkan kata sandi (minimal 8 karakter)"
                       class="block w-full rounded-lg border border-[#e2dac8] bg-white py-2.5 pl-3.5 pr-11 text-sm text-[#161410] placeholder:text-[#b8ae9b] transition focus:border-[#c9934a] focus:outline-none focus:ring-4 focus:ring-[#c9934a]/12">
                <button type="button" data-toggle="password" data-target="password" aria-label="Tampilkan password"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#b8ae9b] transition-colors hover:text-[#7a7264]">
                    <svg data-eye-open width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg data-eye-off width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" class="hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.879 9.879"/>
                    </svg>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="mb-1.5 block text-[13px] font-medium text-[#3a352d]">Konfirmasi password</label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Masukkan kembali kata sandi"
                       class="block w-full rounded-lg border border-[#e2dac8] bg-white py-2.5 pl-3.5 pr-11 text-sm text-[#161410] placeholder:text-[#b8ae9b] transition focus:border-[#c9934a] focus:outline-none focus:ring-4 focus:ring-[#c9934a]/12">
                <button type="button" data-toggle="password" data-target="password_confirmation" aria-label="Tampilkan password"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#b8ae9b] transition-colors hover:text-[#7a7264]">
                    <svg data-eye-open width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <svg data-eye-off width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" class="hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.879 9.879"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-start gap-2.5 pt-1">
            <input id="terms" name="terms" type="checkbox" value="1" required
                   class="mt-0.5 h-4 w-4 cursor-pointer rounded border-[#d6cdb8] text-[#161410] focus:ring-2 focus:ring-[#c9934a]/30 focus:ring-offset-0">
            <label for="terms" class="cursor-pointer select-none text-[12.5px] leading-relaxed text-[#7a7264]">
                Saya menyetujui syarat &amp; ketentuan serta kebijakan privasi StockFlow.
            </label>
        </div>

        <button type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-lg bg-[#161410] px-4 py-3 text-[13.5px] font-semibold text-[#ece7dd] transition-all duration-150 hover:bg-[#2a2520] focus:outline-none focus:ring-4 focus:ring-[#161410]/15 active:scale-[0.99]">
            Buat Akun Admin
            <svg class="h-3.5 w-3.5 text-[#c9934a] transition-transform duration-150 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    <div class="mt-8 border-t border-[#e2dac8] pt-6 text-center">
        <p class="text-[12.5px] text-[#8a8073]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-[#a67b3f] transition-colors hover:text-[#8a6631]">
                Masuk di sini
            </a>
        </p>
    </div>
@endsection
