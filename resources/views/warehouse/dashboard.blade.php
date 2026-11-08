@extends('layouts.warehouse-layout')

@section('page-title', '')

@section('content')
    <!-- ============ MAIN ============ -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- Top bar -->
        <header class="bg-white border-b border-line sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 lg:px-8 h-16 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button class="lg:hidden w-9 h-9 rounded-lg border border-line flex items-center justify-center hover:bg-cream">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-[15px] font-semibold tracking-tight truncate">Dashboard Gudang</h1>
                        <p class="text-[12px] text-muted truncate">Selamat datang, <strong class="text-ink font-medium">Staff Gudang</strong></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-3">
                    <div class="hidden md:block font-mono text-[11.5px] text-muted px-3 py-2 bg-cream rounded-lg border border-line tracking-wide">
                        JUMAT · 07 NOV 2026
                    </div>

                    <button class="relative w-9 h-9 rounded-lg bg-cream border border-line flex items-center justify-center hover:border-gold transition-colors">
                        <svg class="w-[18px] h-[18px] text-muted" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-gold ring-2 ring-white"></span>
                    </button>

                    <div class="flex items-center gap-2.5 pl-2 md:pl-3 md:border-l border-line">
                        <div class="w-9 h-9 rounded-full bg-gold-light text-gold-dark flex items-center justify-center font-semibold text-[13px] ring-2 ring-white shadow-sm">
                            SG
                        </div>
                        <div class="hidden md:block">
                            <div class="text-[13px] font-medium leading-tight">Staff Gudang</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->


            <!-- ============ PO LIST + QUICK ACTIONS ============ -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- PO Menunggu Penerimaan -->
                <div class="lg:col-span-2 bg-white border border-line rounded-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-lineSoft">
                        <div>
                            <div class="text-[14px] font-semibold tracking-tight">PO Menunggu Penerimaan</div>
                            <div class="text-[11.5px] text-muted mt-0.5">Purchase order yang siap diterima</div>
                        </div>
                        <a href="#" class="text-[12.5px] text-gold-dark font-medium hover:text-gold">Lihat semua →</a>
                    </div>

                    <div class="p-2">
                        <div class="flex items-center gap-3.5 px-3 py-3 rounded-xl hover:bg-cream transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11px] font-semibold flex-shrink-0">018</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-semibold">PO-2604-018</div>
                                <div class="text-[11.5px] text-muted truncate">PT Sumber Makmur · ETA Besok, 09:00</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-mono text-[13px] font-semibold tnum">120 <span class="text-[10.5px] text-muted font-normal">unit</span></div>
                                <div class="text-[10.5px] text-muted">Siap diterima</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 px-3 py-3 rounded-xl hover:bg-cream transition-colors border-t border-lineSoft">
                            <div class="w-10 h-10 rounded-lg bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11px] font-semibold flex-shrink-0">017</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-semibold">PO-2604-017</div>
                                <div class="text-[11.5px] text-muted truncate">CV Anugerah Jaya · ETA Hari ini, 14:00</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-mono text-[13px] font-semibold tnum">45 <span class="text-[10.5px] text-muted font-normal">unit</span></div>
                                <div class="text-[10.5px] text-muted">Siap diterima</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 px-3 py-3 rounded-xl hover:bg-cream transition-colors border-t border-lineSoft">
                            <div class="w-10 h-10 rounded-lg bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11px] font-semibold flex-shrink-0">015</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-semibold">PO-2604-015</div>
                                <div class="text-[11.5px] text-muted truncate">UD Berkah Abadi · ETA 2 hari lagi</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-mono text-[13px] font-semibold tnum">300 <span class="text-[10.5px] text-muted font-normal">unit</span></div>
                                <div class="text-[10.5px] text-muted">Siap diterima</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 px-3 py-3 rounded-xl hover:bg-cream transition-colors border-t border-lineSoft">
                            <div class="w-10 h-10 rounded-lg bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11px] font-semibold flex-shrink-0">012</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[13px] font-semibold">PO-2604-012</div>
                                <div class="text-[11.5px] text-muted truncate">PT Global Supply · ETA 3 hari lagi</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="font-mono text-[13px] font-semibold tnum">80 <span class="text-[10.5px] text-muted font-normal">unit</span></div>
                                <div class="text-[10.5px] text-muted">Siap diterima</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-line rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-lineSoft">
                        <div class="text-[14px] font-semibold tracking-tight">Quick Actions</div>
                        <div class="text-[11.5px] text-muted mt-0.5">Ringkasan & aksi cepat</div>
                    </div>
                    <div class="p-3 space-y-2">

                        <a href="#" class="group flex items-center gap-3 p-3 rounded-xl border border-line hover:border-gold hover:bg-gold-light transition-colors">
                            <span class="w-7 h-7 rounded-md bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11.5px] font-semibold group-hover:bg-gold group-hover:text-white transition-colors flex-shrink-0">1</span>
                            <span class="flex-1 min-w-0">
                                <span class="block text-[12.5px] font-semibold group-hover:text-gold-dark transition-colors">PO Siap Diterima</span>
                                <span class="block text-[11px] text-muted">4 PO menunggu</span>
                            </span>
                            <svg class="w-4 h-4 text-dim group-hover:text-gold-dark transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>

                        <a href="#" class="group flex items-center gap-3 p-3 rounded-xl border border-line hover:border-danger hover:bg-danger-bg transition-colors">
                            <span class="w-7 h-7 rounded-md bg-danger-bg text-danger flex items-center justify-center font-mono text-[11.5px] font-semibold group-hover:bg-danger group-hover:text-white transition-colors flex-shrink-0">2</span>
                            <span class="flex-1 min-w-0">
                                <span class="block text-[12.5px] font-semibold group-hover:text-danger transition-colors">Stok Kritis / Menipis</span>
                                <span class="block text-[11px] text-muted">23 item perlu restock</span>
                            </span>
                            <svg class="w-4 h-4 text-dim group-hover:text-danger transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>

                        <a href="#" class="group flex items-center gap-3 p-3 rounded-xl border border-line hover:border-success hover:bg-success-bg transition-colors">
                            <span class="w-7 h-7 rounded-md bg-success-bg text-success flex items-center justify-center font-mono text-[11.5px] font-semibold group-hover:bg-success group-hover:text-white transition-colors flex-shrink-0">3</span>
                            <span class="flex-1 min-w-0">
                                <span class="block text-[12.5px] font-semibold group-hover:text-success transition-colors">Barang Masuk Hari Ini</span>
                                <span class="block text-[11px] text-muted">18 item diterima</span>
                            </span>
                            <svg class="w-4 h-4 text-dim group-hover:text-success transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>

                        <a href="#" class="group flex items-center gap-3 p-3 rounded-xl border border-line hover:border-gold hover:bg-gold-light transition-colors">
                            <span class="w-7 h-7 rounded-md bg-gold-light text-gold-dark flex items-center justify-center font-mono text-[11.5px] font-semibold group-hover:bg-gold group-hover:text-white transition-colors flex-shrink-0">4</span>
                            <span class="flex-1 min-w-0">
                                <span class="block text-[12.5px] font-semibold group-hover:text-gold-dark transition-colors">Adjustment Pending</span>
                                <span class="block text-[11px] text-muted">6 menunggu approval</span>
                            </span>
                            <svg class="w-4 h-4 text-dim group-hover:text-gold-dark transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>

                    </div>
                </div>
            </div>

            <!-- ============ TABEL STOK MENIPIS ============ -->
            <div class="bg-white border border-line rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-lineSoft">
                    <div>
                        <div class="text-[14px] font-semibold tracking-tight">Stok Menipis / Kritis</div>
                        <div class="text-[11.5px] text-muted mt-0.5">Produk yang perlu segera direstock</div>
                    </div>
                    <a href="#" class="text-[12.5px] text-gold-dark font-medium hover:text-gold">Kelola stok →</a>
                </div>

                <div class="overflow-x-auto scroll-clean">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="bg-[#fbf9f5] border-b border-lineSoft">
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Produk</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">SKU</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Stok Saat Ini</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Stok Min.</th>
                                <th class="text-left px-5 py-3 text-[10.5px] font-semibold uppercase tracking-wider text-muted">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-lineSoft hover:bg-[#fbf9f5]">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">KR</div>
                                        <div>
                                            <div class="text-[13px] font-semibold">Kertas HVS A4 80gsm</div>
                                            <div class="text-[11.5px] text-muted">Kategori umum</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-muted">KRT-A4-80</td>
                                <td class="px-5 py-3.5 font-mono font-semibold text-danger tnum">12</td>
                                <td class="px-5 py-3.5 font-mono text-muted tnum">50</td>
                                <td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-bg text-danger text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kritis</span></td>
                            </tr>
                            <tr class="border-b border-lineSoft hover:bg-[#fbf9f5]">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">TN</div>
                                        <div>
                                            <div class="text-[13px] font-semibold">Tinta Printer Black</div>
                                            <div class="text-[11.5px] text-muted">Kategori umum</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-muted">TNT-BLK-01</td>
                                <td class="px-5 py-3.5 font-mono font-semibold text-danger tnum">8</td>
                                <td class="px-5 py-3.5 font-mono text-muted tnum">25</td>
                                <td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-bg text-danger text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kritis</span></td>
                            </tr>
                            <tr class="border-b border-lineSoft hover:bg-[#fbf9f5]">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">MA</div>
                                        <div>
                                            <div class="text-[13px] font-semibold">Map Plastik F4</div>
                                            <div class="text-[11.5px] text-muted">Kategori umum</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-muted">MAP-F4-02</td>
                                <td class="px-5 py-3.5 font-mono font-semibold text-gold-dark tnum">34</td>
                                <td class="px-5 py-3.5 font-mono text-muted tnum">60</td>
                                <td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-light text-gold-dark text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Menipis</span></td>
                            </tr>
                            <tr class="border-b border-lineSoft hover:bg-[#fbf9f5]">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">PL</div>
                                        <div>
                                            <div class="text-[13px] font-semibold">Pulpen Gel Hitam</div>
                                            <div class="text-[11.5px] text-muted">Kategori umum</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-muted">PLP-GEL-BK</td>
                                <td class="px-5 py-3.5 font-mono font-semibold text-gold-dark tnum">18</td>
                                <td class="px-5 py-3.5 font-mono text-muted tnum">40</td>
                                <td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-light text-gold-dark text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Menipis</span></td>
                            </tr>
                            <tr class="hover:bg-[#fbf9f5]">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-[#f5efe3] text-gold-dark flex items-center justify-center font-mono text-[10.5px] font-semibold flex-shrink-0">ST</div>
                                        <div>
                                            <div class="text-[13px] font-semibold">Stapler Medium</div>
                                            <div class="text-[11.5px] text-muted">Kategori umum</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-[12px] text-muted">STP-MD-01</td>
                                <td class="px-5 py-3.5 font-mono font-semibold text-danger tnum">5</td>
                                <td class="px-5 py-3.5 font-mono text-muted tnum">15</td>
                                <td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger-bg text-danger text-[11px] font-medium"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kritis</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        
    </div>
</div>

</body>
</html>
@endsection