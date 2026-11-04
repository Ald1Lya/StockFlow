@extends('layouts.admin-layout')

@section('page-title', 'Dashboard')

@section('content')
<section><div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Overview</div>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Ringkasan Operasional</h1>
                        <p class="mt-1 text-[13px] text-[#7a7264]">Data per <span class="font-medium text-[#161410]">2 November 2026</span> · update terakhir 5 menit lalu.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="inline-flex items-center gap-1.5 rounded-md border border-[#e2dac8] bg-white px-3 py-2 text-[12.5px] font-medium text-[#3a352d] hover:bg-[#f7f4ee]">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Export
                        </button>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                            <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Tambah Produk
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">

                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="flex items-start justify-between">
                            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Total Pendapatan</div>
                            <span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10px] font-semibold text-[#4a6a4a]">+12.5%</span>
                        </div>
                        <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">Rp 248.5jt</div>
                        <div class="mt-1 text-[11px] text-[#a89f8e]">vs bulan lalu · Rp 221.0jt</div>
                    </div>

                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="flex items-start justify-between">
                            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Pesanan</div>
                            <span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10px] font-semibold text-[#4a6a4a]">+8.2%</span>
                        </div>
                        <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">1,284</div>
                        <div class="mt-1 text-[11px] text-[#a89f8e]">94 menunggu diproses</div>
                    </div>

                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="flex items-start justify-between">
                            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#8a8073]">Produk Aktif</div>
                            <span class="rounded bg-[#161410]/5 px-1.5 py-0.5 text-[10px] font-semibold text-[#3a352d]">342 SKU</span>
                        </div>
                        <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">342</div>
                        <div class="mt-1 text-[11px] text-[#a89f8e]">8 jenis material · 4 gudang</div>
                    </div>

                    <div class="rounded-lg border border-[#c9934a]/40 bg-[#fdf9f0] p-4">
                        <div class="flex items-start justify-between">
                            <div class="text-[11.5px] font-medium uppercase tracking-wider text-[#a67b3f]">Stok Rendah</div>
                            <span class="rounded bg-[#c9934a]/15 px-1.5 py-0.5 text-[10px] font-semibold text-[#a67b3f]">Perlu restock</span>
                        </div>
                        <div class="mt-2 font-mono text-2xl font-semibold tnum text-[#161410]">18</div>
                        <div class="mt-1 text-[11px] text-[#a67b3f]/80">di bawah batas minimum</div>
                    </div>

                </div>

                <!-- Chart + Top Products -->
                <div class="mt-4 grid gap-4 xl:grid-cols-3">

                    <!-- Chart -->
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-5 xl:col-span-2">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-[13px] font-semibold text-[#161410]">Tren Pendapatan</div>
                                <div class="text-[11.5px] text-[#8a8073]">7 bulan terakhir · dalam juta rupiah</div>
                            </div>
                            <div class="flex gap-1 rounded-md border border-[#e2dac8] p-0.5">
                                <button class="rounded bg-[#161410] px-2 py-0.5 text-[10.5px] font-semibold text-[#ece7dd]">Bulan</button>
                                <button class="rounded px-2 py-0.5 text-[10.5px] font-medium text-[#8a8073] hover:bg-[#f7f4ee]">Minggu</button>
                            </div>
                        </div>

                        <div class="mt-5">
                            <svg viewBox="0 0 600 210" class="w-full" preserveAspectRatio="none" style="height:210px">
                                <defs>
                                    <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#c9934a" stop-opacity="0.22"/>
                                        <stop offset="100%" stop-color="#c9934a" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <!-- grid lines -->
                                <line x1="40" y1="30" x2="560" y2="30" stroke="#e2dac8" stroke-dasharray="3 3"/>
                                <line x1="40" y1="70" x2="560" y2="70" stroke="#e2dac8" stroke-dasharray="3 3"/>
                                <line x1="40" y1="110" x2="560" y2="110" stroke="#e2dac8" stroke-dasharray="3 3"/>
                                <line x1="40" y1="150" x2="560" y2="150" stroke="#e2dac8" stroke-dasharray="3 3"/>
                                <line x1="40" y1="180" x2="560" y2="180" stroke="#e2dac8"/>

                                <!-- area -->
                                <path d="M40,110 L127,82 L213,88 L300,60 L387,70 L473,45 L560,25 L560,180 L40,180 Z" fill="url(#areaFill)"/>
                                <!-- line -->
                                <polyline points="40,110 127,82 213,88 300,60 387,70 473,45 560,25"
                                          fill="none" stroke="#c9934a" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
                                <!-- points -->
                                <circle cx="560" cy="25" r="4" fill="#161410"/>
                                <circle cx="560" cy="25" r="8" fill="#c9934a" opacity="0.2"/>

                                <!-- labels -->
                                <g fill="#8a8073" font-family="ui-sans-serif" font-size="10">
                                    <text x="40" y="200" text-anchor="middle">Jan</text>
                                    <text x="127" y="200" text-anchor="middle">Feb</text>
                                    <text x="213" y="200" text-anchor="middle">Mar</text>
                                    <text x="300" y="200" text-anchor="middle">Apr</text>
                                    <text x="387" y="200" text-anchor="middle">Mei</text>
                                    <text x="473" y="200" text-anchor="middle">Jun</text>
                                    <text x="560" y="200" text-anchor="middle">Jul</text>
                                </g>
                                <g fill="#a89f8e" font-family="ui-monospace" font-size="9">
                                    <text x="30" y="34" text-anchor="end">250</text>
                                    <text x="30" y="74" text-anchor="end">180</text>
                                    <text x="30" y="114" text-anchor="end">120</text>
                                    <text x="30" y="154" text-anchor="end">60</text>
                                    <text x="30" y="184" text-anchor="end">0</text>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-[13px] font-semibold text-[#161410]">Performa Produk</div>
                            <a href="{{ route('admin.products.index') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Semua</a>
                        </div>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">01</span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[12.5px] font-medium text-[#161410]">Semen Portland 50 kg</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-SMN-8821</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">1,240</div>
                                    <div class="text-[10px] text-[#a89f8e]">terjual</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">02</span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[12.5px] font-medium text-[#161410]">Besi Beton 10 mm</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-BSB-4410</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">980</div>
                                    <div class="text-[10px] text-[#a89f8e]">terjual</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">03</span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[12.5px] font-medium text-[#161410]">Cat Tembok 5 kg</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-CTT-2290</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">865</div>
                                    <div class="text-[10px] text-[#a89f8e]">terjual</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">04</span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[12.5px] font-medium text-[#161410]">Paku Beton 7 cm</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-PKB-1102</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">642</div>
                                    <div class="text-[10px] text-[#a89f8e]">terjual</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-[#f7f4ee] font-mono text-[10px] font-semibold text-[#a67b3f]">05</span>
                                <div class="min-w-0 flex-1">
                                    <div class="truncate text-[12.5px] font-medium text-[#161410]">Pipa PVC 3 inci</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-PPV-7754</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#161410]">521</div>
                                    <div class="text-[10px] text-[#a89f8e]">terjual</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recent + Low stock -->
                <div class="mt-4 grid gap-4 xl:grid-cols-3">

                    <!-- Recent transactions -->
                    <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white xl:col-span-2">
                        <div class="flex items-center justify-between border-b border-[#e2dac8] px-5 py-3.5">
                            <div class="text-[13px] font-semibold text-[#161410]">Transaksi Terbaru</div>
                            <a href="{{ route('admin.reports.sales') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Lihat semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[12.5px]">
                                <thead>
                                    <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                                        <th class="px-5 py-2.5 font-semibold">Invoice</th>
                                        <th class="px-3 py-2.5 font-semibold">Kasir</th>
                                        <th class="px-3 py-2.5 font-semibold">Total</th>
                                        <th class="px-3 py-2.5 font-semibold">Status</th>
                                        <th class="px-5 py-2.5 text-right font-semibold">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">INV-2026-1184</td>
                                        <td class="px-3 py-3">Rina S.</td>
                                        <td class="px-3 py-3 font-mono tnum">Rp 4.250.000</td>
                                        <td class="px-3 py-3"><span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#4a6a4a]">Lunas</span></td>
                                        <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">10:42</td>
                                    </tr>
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">INV-2026-1183</td>
                                        <td class="px-3 py-3">Dimas P.</td>
                                        <td class="px-3 py-3 font-mono tnum">Rp 1.890.000</td>
                                        <td class="px-3 py-3"><span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#4a6a4a]">Lunas</span></td>
                                        <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">10:18</td>
                                    </tr>
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">INV-2026-1182</td>
                                        <td class="px-3 py-3">Rina S.</td>
                                        <td class="px-3 py-3 font-mono tnum">Rp 750.000</td>
                                        <td class="px-3 py-3"><span class="rounded bg-[#c9934a]/15 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#a67b3f]">Pending</span></td>
                                        <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">09:55</td>
                                    </tr>
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">INV-2026-1181</td>
                                        <td class="px-3 py-3">Agus W.</td>
                                        <td class="px-3 py-3 font-mono tnum">Rp 3.120.000</td>
                                        <td class="px-3 py-3"><span class="rounded bg-[#5a7a5a]/10 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#4a6a4a]">Lunas</span></td>
                                        <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">09:30</td>
                                    </tr>
                                    <tr class="hover:bg-[#f7f4ee]/50">
                                        <td class="px-5 py-3 font-mono text-[11.5px] font-medium text-[#161410]">INV-2026-1180</td>
                                        <td class="px-3 py-3">Dimas P.</td>
                                        <td class="px-3 py-3 font-mono tnum">Rp 980.000</td>
                                        <td class="px-3 py-3"><span class="rounded bg-[#a04a3a]/10 px-1.5 py-0.5 text-[10.5px] font-semibold text-[#a04a3a]">Batal</span></td>
                                        <td class="px-5 py-3 text-right text-[11.5px] text-[#8a8073]">09:12</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Low stock -->
                    <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
                        <div class="flex items-center justify-between border-b border-[#e2dac8] px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#c9934a]"></span>
                                <div class="text-[13px] font-semibold text-[#161410]">Stok Rendah</div>
                            </div>
                            <a href="{{ route('admin.reports.inventory') }}" class="text-[11.5px] font-medium text-[#a67b3f] hover:underline">Detail</a>
                        </div>
                        <ul class="divide-y divide-[#e2dac8]/70">
                            <li class="flex items-center justify-between px-5 py-3">
                                <div>
                                    <div class="text-[12.5px] font-medium text-[#161410]">Besi Beton 10 mm</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-BSB-4410</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#a67b3f]">4</div>
                                    <div class="text-[10px] text-[#a89f8e]">/ min 20</div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between px-5 py-3">
                                <div>
                                    <div class="text-[12.5px] font-medium text-[#161410]">Paku Beton 7 cm</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-PKB-1102</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#a67b3f]">7</div>
                                    <div class="text-[10px] text-[#a89f8e]">/ min 25</div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between px-5 py-3">
                                <div>
                                    <div class="text-[12.5px] font-medium text-[#161410]">Pipa PVC 3 inci</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-PPV-7754</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#a67b3f]">12</div>
                                    <div class="text-[10px] text-[#a89f8e]">/ min 30</div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between px-5 py-3">
                                <div>
                                    <div class="text-[12.5px] font-medium text-[#161410]">Semen Portland 50 kg</div>
                                    <div class="font-mono text-[10.5px] text-[#a89f8e]">SKU-SMN-8821</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono text-[12px] font-semibold tnum text-[#a67b3f]">15</div>
                                    <div class="text-[10px] text-[#a89f8e]">/ min 50</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div></section>
@endsection
