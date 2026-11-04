@extends('layouts.auth-layout')

@section('title', 'Masuk · StockFlow')

@section('content')
    <div class="mb-8">
        <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-[#a67b3f]">Sign in</p>
        <h2 class="mt-2.5 text-[28px] leading-tight tracking-tight text-[#161410]"
            style="font-family:'Fraunces', ui-serif, Georgia, serif;">
            Selamat datang kembali
        </h2>
        <p class="mt-2 text-sm text-[#7a7264]">
            Pilih peran Anda, lalu masuk untuk melanjutkan.
        </p>
    </div>

    <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="mb-2 block text-[13px] font-medium text-[#3a352d]">Masuk sebagai</label>
            <div class="grid grid-cols-3 gap-2.5">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="admin" class="peer sr-only" @checked(old('role', 'admin') === 'admin')>
                    <div class="flex flex-col items-center gap-1.5 rounded-lg border border-[#e2dac8] bg-white px-2 py-3.5 text-center transition duration-150 hover:border-[#c9934a]/40 peer-checked:border-[#c9934a] peer-checked:bg-[#fdf9f0] peer-checked:[&_svg]:text-[#a67b3f] peer-checked:[&_span]:text-[#161410] peer-focus-visible:ring-2 peer-focus-visible:ring-[#c9934a]/25 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#f7f4ee]">
                        <svg class="h-[18px] w-[18px] text-[#b8ae9b] transition-colors" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3l7 3v5.25c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/>
                        </svg>
                        <span class="text-[10.5px] font-semibold uppercase tracking-wider text-[#7a7264] transition-colors">Admin</span>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="role" value="warehouse" class="peer sr-only" @checked(old('role') === 'warehouse')>
                    <div class="flex flex-col items-center gap-1.5 rounded-lg border border-[#e2dac8] bg-white px-2 py-3.5 text-center transition duration-150 hover:border-[#c9934a]/40 peer-checked:border-[#c9934a] peer-checked:bg-[#fdf9f0] peer-checked:[&_svg]:text-[#a67b3f] peer-checked:[&_span]:text-[#161410] peer-focus-visible:ring-2 peer-focus-visible:ring-[#c9934a]/25 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#f7f4ee]">
                        <svg class="h-[18px] w-[18px] text-[#b8ae9b] transition-colors" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5 12 3 3 7.5m18 0L12 12m9-4.5v9L12 21m0-9L3 7.5m9 4.5v9M3 7.5v9L12 21"/>
                        </svg>
                        <span class="text-[10.5px] font-semibold uppercase tracking-wider text-[#7a7264] transition-colors">Gudang</span>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="role" value="cashier" class="peer sr-only" @checked(old('role') === 'cashier')>
                    <div class="flex flex-col items-center gap-1.5 rounded-lg border border-[#e2dac8] bg-white px-2 py-3.5 text-center transition duration-150 hover:border-[#c9934a]/40 peer-checked:border-[#c9934a] peer-checked:bg-[#fdf9f0] peer-checked:[&_svg]:text-[#a67b3f] peer-checked:[&_span]:text-[#161410] peer-focus-visible:ring-2 peer-focus-visible:ring-[#c9934a]/25 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#f7f4ee]">
                        <svg class="h-[18px] w-[18px] text-[#b8ae9b] transition-colors" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                        </svg>
                        <span class="text-[10.5px] font-semibold uppercase tracking-wider text-[#7a7264] transition-colors">Kasir</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-[13px] font-medium text-[#3a352d]">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   placeholder="nama@stockflow.id"
                   class="block w-full rounded-lg border border-[#e2dac8] bg-white px-3.5 py-2.5 text-sm text-[#161410] placeholder:text-[#b8ae9b] transition focus:border-[#c9934a] focus:outline-none focus:ring-4 focus:ring-[#c9934a]/12">
        </div>

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="block text-[13px] font-medium text-[#3a352d]">Password</label>
                <a href="#" class="text-[12px] font-medium text-[#a67b3f] transition-colors hover:text-[#8a6631]">
                    Lupa password?
                </a>
            </div>
            <div class="relative">
                <input type="password" name="password" id="password" required placeholder="••••••••"
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

        <div class="flex items-center gap-2.5">
            <input id="remember_me" name="remember" type="checkbox" value="1" @checked(old('remember'))
                   class="h-4 w-4 cursor-pointer rounded border-[#d6cdb8] text-[#161410] focus:ring-2 focus:ring-[#c9934a]/30 focus:ring-offset-0">
            <label for="remember_me" class="cursor-pointer select-none text-[13px] text-[#7a7264]">
                Ingat saya di perangkat ini
            </label>
        </div>

        <button type="submit"
                class="group flex w-full items-center justify-center gap-2 rounded-lg bg-[#161410] px-4 py-3 text-[13.5px] font-semibold text-[#ece7dd] transition-all duration-150 hover:bg-[#2a2520] focus:outline-none focus:ring-4 focus:ring-[#161410]/15 active:scale-[0.99]">
            Masuk ke Sistem
            <svg class="h-3.5 w-3.5 text-[#c9934a] transition-transform duration-150 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    <div class="mt-8 border-t border-[#e2dac8] pt-6 text-center">
        <p class="text-[11.5px] leading-relaxed text-[#8a8073]">
            Akses dibatasi berdasarkan peran. Butuh bantuan?
            Hubungi <span class="font-semibold text-[#3a352d]">administrator</span>.
        </p>
        @if ($canRegisterAdmin)
            <p class="mt-4 text-[12.5px] text-[#8a8073]">
                Belum ada admin?
                <a href="{{ route('register') }}" class="font-semibold text-[#a67b3f] transition-colors hover:text-[#8a6631]">
                    Buat akun admin pertama
                </a>
            </p>
        @endif
    </div>

    @if (session('admin_registered'))
        {{-- Modal ini khusus konfirmasi register admin pertama; popup fitur/role lain punya desainnya sendiri nanti. --}}
        <x-modal
            :show="true"
            title="Akun admin berhasil dibuat"
            message="Akun administrator pertama sudah siap. Silakan masuk menggunakan email dan password yang baru dibuat."
            :action-url="route('login')"
            action-text="Lanjut ke login"
        />
    @endif
@endsection
