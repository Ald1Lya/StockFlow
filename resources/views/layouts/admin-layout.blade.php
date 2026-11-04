<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Dashboard') - Admin StockFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .tnum { font-variant-numeric: tabular-nums; }
        .nav-item.active {
            background: rgba(201,147,74,0.10);
            color: #ece7dd;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 8px; bottom: 8px;
            width: 2px;
            background: #c9934a;
            border-radius: 2px;
        }
        .scroll-clean::-webkit-scrollbar { width: 6px; height: 6px; }
        .scroll-clean::-webkit-scrollbar-thumb { background: #d6cdb8; border-radius: 3px; }
        .scroll-clean::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>

<body class="bg-[#f7f4ee] text-[#161410] antialiased">

<div class="flex h-screen overflow-hidden">

    <!-- ============================================================
         SIDEBAR
    ============================================================= -->
    <aside class="hidden w-[248px] shrink-0 flex-col bg-[#161410] lg:flex">

        <!-- Brand -->
        <div class="flex items-center gap-2.5 border-b border-white/[0.06] px-5 py-5">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#c9934a]/15 text-[#c9934a]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m3 10 9-7 9 7M5.5 9v11h13V9M9 20v-6h6v6M3 20h18"/>
                </svg>
            </span>
            <div class="leading-tight">
                <div class="text-[13px] font-semibold text-[#ece7dd]">StockFlow</div>
                <div class="text-[10px] uppercase tracking-[0.16em] text-[#5a544c]">Toko Bangunan</div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 overflow-y-auto scroll-clean px-3 py-3">

            <div class="px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#5a544c]">Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
                Dashboard
            </a>

            <div class="mt-4 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#5a544c]">Master Data</div>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.index') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                Users
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-item {{ request()->routeIs('admin.categories.index') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                Kategori
            </a>

            <a href="{{ route('admin.brands.index') }}"
               class="nav-item {{ request()->routeIs('admin.brands.*') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 3.75 7.5 12 12l8.25-4.5L12 3ZM3.75 12 12 16.5 20.25 12M3.75 16.5 12 21l8.25-4.5"/>
                </svg>
                Brand
            </a>

            <a href="{{ route('admin.suppliers.index') }}"
               class="nav-item {{ request()->routeIs('admin.suppliers.index') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
                Supplier
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="nav-item {{ request()->routeIs('admin.products.index') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </svg>
                Produk
            </a>

            <div class="mt-4 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#5a544c]">Laporan</div>

            <a href="{{ route('admin.reports.sales') }}"
               class="nav-item {{ request()->routeIs('admin.reports.sales') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                </svg>
                Penjualan
            </a>

            <a href="{{ route('admin.reports.inventory') }}"
               class="nav-item {{ request()->routeIs('admin.reports.inventory') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                </svg>
                Inventori
            </a>

        </nav>


<!-- User card -->
<div class="border-t border-white/[0.06] p-3">
    <div class="flex items-center gap-2.5 rounded-md p-2 transition-colors hover:bg-white/[0.04]">

        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#c9934a] font-semibold text-[11px] text-[#161410]">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </span>

        <div class="min-w-0 flex-1">
            <div class="truncate text-[12px] font-semibold text-[#ece7dd]">
                {{ auth()->user()->name }}
            </div>

            <div class="truncate text-[10px] text-[#5a544c]">
                {{ auth()->user()->email }}
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                title="Logout"
                aria-label="Logout"
                class="rounded-md p-2 text-[#8a8073] transition-colors hover:bg-red-500/10 hover:text-red-400"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                </svg>
            </button>
        </form>
    </div>
</div>



    </aside>

    <!-- ============================================================
         MAIN AREA
    ============================================================= -->
    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

        <!-- Topbar -->
        <header class="flex h-14 shrink-0 items-center justify-between gap-4 border-b border-[#e2dac8] bg-[#f7f4ee]/80 px-5 backdrop-blur">
            <div class="flex items-center gap-3">
                <button class="rounded-md p-1.5 text-[#7a7264] hover:bg-[#e2dac8]/60 lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <nav class="hidden items-center gap-1.5 text-[12px] text-[#8a8073] sm:flex">
                    <span>Admin</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                    <span class="font-semibold text-[#161410]">@yield('page-title', 'Dashboard')</span>
                </nav>
            </div>

            <div class="flex items-center gap-2">
                <!-- Search -->
                <div class="relative hidden sm:block">
                </div>

                <div class="hidden h-6 w-px bg-[#e2dac8] sm:block"></div>
                <button class="flex items-center gap-2 rounded-md py-1 pl-1 pr-2 hover:bg-[#e2dac8]/60">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#161410] font-semibold text-[11px] text-[#c9934a]">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    <svg class="hidden h-3.5 w-3.5 text-[#7a7264] sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="scroll-clean flex-1 overflow-y-auto p-5 sm:p-7">

            @yield('content')

        </main>
    </div>
</div>

</body>
</html>