<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Kasir') - Toko Bangunan StockFlow</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23161410'/><text x='50%25' y='55%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-weight='bold' font-size='55' fill='%23c9934a'>S</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f7f4ee] font-sans text-[#161410] antialiased h-screen overflow-hidden flex">

    <!-- ================= SIDEBAR NAVBAR ================= -->
    <aside class="w-64 bg-[#161410] text-[#ece7dd] flex flex-col h-full flex-shrink-0 shadow-xl print:hidden">
        <div class="h-16 flex items-center px-6 border-b border-white/10">
            <svg class="w-6 h-6 text-[#c9934a] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
            <span class="text-white font-bold text-lg tracking-wider">TOKO BANGUNAN</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- 1. Dashboard -->
            <a href="{{ route('cashier.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-[13.5px] transition-colors {{ request()->routeIs('cashier.dashboard') ? 'bg-[#c9934a]/15 text-[#c9934a] border border-[#c9934a]/30' : 'text-[#8a8073] hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Dashboard
            </a>

            <!-- 2. Belanja (POS + Nota) -->
            <a href="{{ route('cashier.pos.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-[13.5px] transition-colors {{ request()->routeIs('cashier.pos.*') ? 'bg-[#c9934a]/15 text-[#c9934a] border border-[#c9934a]/30' : 'text-[#8a8073] hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                Belanja (POS)
            </a>

            <!-- 3. Riwayat Belanja -->
            <a href="{{ route('cashier.histories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-[13.5px] transition-colors {{ request()->routeIs('cashier.histories.*') ? 'bg-[#c9934a]/15 text-[#c9934a] border border-[#c9934a]/30' : 'text-[#8a8073] hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Belanja
            </a>
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/20 space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#c9934a] text-[#161410] flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'KS', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-[13px] font-medium text-white truncate">{{ Auth::user()->name ?? 'Kasir' }}</div>
                    <div class="text-[11px] text-[#8a8073]">Kasir Aktif</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-md border border-white/15 py-1.5 text-xs text-[#ece7dd] hover:bg-white/10 transition-colors">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>