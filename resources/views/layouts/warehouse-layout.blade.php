<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang — StockFlow</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold:      { DEFAULT: '#c9934a', dark: '#a67838', light: '#f5ede0' },
                        cream:     '#faf8f4',
                        sidebar:   '#1a1714',
                        ink:       '#1a1714',
                        muted:     '#8a8175',
                        dim:       '#b5ac9c',
                        line:      '#e8e2d5',
                        lineSoft:  '#f0ebde',
                        success:   { DEFAULT: '#4a7c59', bg: '#eaf2ec' },
                        danger:    { DEFAULT: '#b85450', bg: '#f9ecec' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .tnum { font-variant-numeric: tabular-nums; }
        .scroll-clean::-webkit-scrollbar { width: 6px; height: 6px; }
        .scroll-clean::-webkit-scrollbar-thumb { background: #d6cdb8; border-radius: 3px; }
    </style>
</head>
<body class="bg-cream text-ink antialiased">

<div class="flex min-h-screen">

    <!-- ============ SIDEBAR ============ -->
    <aside class="w-60 bg-sidebar text-[#ece7dd] flex-shrink-0 hidden lg:flex flex-col sticky top-0 h-screen">

        <!-- Logo -->
        <div class="px-5 py-5 border-b border-white/5">
            <a href="#" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg bg-gold flex items-center justify-center shadow-lg shadow-gold/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-[15px] tracking-tight">StockFlow</div>
                    <div class="text-[10.5px] text-white/40 font-mono tracking-wider">Gudang</div>
                </div>
            </a>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            <!-- Active -->
 
            <a href="{{ route('warehouse.dashboard') }}"
               class="nav-item {{ request()->routeIs('warehouse.dashboard') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('warehouse.iventorys.index') }}"
               class="nav-item {{ request()->routeIs('warehouse.iventorys.index') ? 'active ' : '' }}relative flex items-center gap-2.5 rounded-md px-3 py-2 text-[13px] font-medium text-[#8a8073] transition-colors hover:bg-white/[0.04] hover:text-[#ece7dd]">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                Iventory
            </a>

            {{-- <a href=""
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
            </a> --}}

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


        <main class="flex-1 px-6 lg:px-8 py-6 lg:py-7 space-y-5 max-w-[1400px] w-full mx-auto">
         
            @yield('content')

        </main>

</div>

</body>
</html>