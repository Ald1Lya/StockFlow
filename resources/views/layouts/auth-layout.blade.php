<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StockFlow')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,400;9..144,500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f4ee] text-[#161410] antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[1.05fr_1fr]">
        <aside class="relative hidden overflow-hidden bg-[#161410] p-12 text-[#8a8073] lg:flex lg:flex-col xl:p-16">
            <div class="pointer-events-none absolute -right-40 -top-32 h-[420px] w-[420px] rounded-full bg-[#c9934a]/10 blur-[120px]"></div>

            <div class="relative flex items-center gap-2.5">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#c9934a]/15 text-[#c9934a]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </span>
                <span class="text-[15px] font-semibold tracking-tight text-[#ece7dd]">StockFlow</span>
            </div>

            <div class="relative my-auto max-w-sm">
                <p class="text-[11px] font-medium uppercase tracking-[0.24em] text-[#c9934a]">
                    Inventory &amp; Order
                </p>

                <h1 class="mt-5 text-[2.5rem] leading-[1.08] tracking-tight text-[#ece7dd] xl:text-[2.85rem]"
                    style="font-family:'Fraunces', ui-serif, Georgia, serif;">
                    Stok rapi,<br>pesanan terkendali.
                </h1>

                <p class="mt-5 text-sm leading-relaxed text-[#8a8073]">
                    Satu sistem untuk inventori multi-gudang dan alur pesanan.
                    Tanpa spreadsheet, tanpa tebak-tebakan.
                </p>

                <dl class="mt-12 divide-y divide-white/[0.06] border-y border-white/[0.06]">
                    <div class="flex items-baseline justify-between py-4">
                        <dt class="text-sm text-[#c9c3b6]">Stok real-time</dt>
                        <dd class="text-[11px] uppercase tracking-wider text-[#5a544c]">Multi-gudang</dd>
                    </div>
                    <div class="flex items-baseline justify-between py-4">
                        <dt class="text-sm text-[#c9c3b6]">Pesanan terpusat</dt>
                        <dd class="text-[11px] uppercase tracking-wider text-[#5a544c]">Satu alur</dd>
                    </div>
                    <div class="flex items-baseline justify-between py-4">
                        <dt class="text-sm text-[#c9c3b6]">Akses per peran</dt>
                        <dd class="text-[11px] uppercase tracking-wider text-[#5a544c]">Admin · Gudang · Kasir</dd>
                    </div>
                </dl>
            </div>

            <div class="relative flex items-center justify-between text-[11px] text-[#5a544c]">
                <span>© 2026 StockFlow</span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-1 w-1 rounded-full bg-[#c9934a]"></span>
                    v2.4
                </span>
            </div>
        </aside>

        <main class="flex min-h-screen items-center justify-center bg-[#f7f4ee] p-6 sm:p-10">
            <div class="w-full max-w-[420px]">
                <div class="mb-8 flex items-center gap-2.5 lg:hidden">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-md bg-[#161410] text-[#c9934a]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </span>
                    <span class="text-[15px] font-semibold tracking-tight text-[#161410]">StockFlow</span>
                </div>

                @if (session('status'))
                    <x-alert type="info" class="mb-6">
                        {{ session('status') }}
                    </x-alert>
                @endif

                @if ($errors->any())
                    <x-alert type="error" class="mb-6">
                        {{ $errors->first() }}
                    </x-alert>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('[data-toggle="password"]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const show = input.type === 'password';

                input.type = show ? 'text' : 'password';
                button.querySelector('[data-eye-open]').classList.toggle('hidden', show);
                button.querySelector('[data-eye-off]').classList.toggle('hidden', !show);
            });
        });
    </script>
</body>
</html>