@extends('layouts.admin-layout')

@section('page-title', 'Laporan Penjualan')

@section('content')
<section><div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Laporan</div>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Laporan Penjualan</h1>
                        <p class="mt-1 text-[13px] text-[#7a7264]">Periode 1 Okt – 31 Okt 2026</p>
                    </div>
                    <button class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                        <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Export PDF / Excel
                    </button>
                </div>

                <!-- Filter bar -->
                <div class="mb-4 rounded-lg border border-[#e2dac8] bg-white p-3">
                    <div class="grid gap-2 sm:grid-cols-4">
                        <div>
                            <label class="mb-1 block text-[10.5px] font-semibold uppercase tracking-wider text-[#8a8073]">Dari</label>
                            <input type="date" value="2026-10-01" class="w-full rounded-md border border-[#e2dac8] bg-white px-2.5 py-1.5 text-[12.5px] font-mono focus:border-[#c9934a] focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10.5px] font-semibold uppercase tracking-wider text-[#8a8073]">Sampai</label>
                            <input type="date" value="2026-10-31" class="w-full rounded-md border border-[#e2dac8] bg-white px-2.5 py-1.5 text-[12.5px] font-mono focus:border-[#c9934a] focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10.5px] font-semibold uppercase tracking-wider text-[#8a8073]">Kasir</label>
                            <select class="w-full rounded-md border border-[#e2dac8] bg-white px-2.5 py-1.5 text-[12.5px] focus:border-[#c9934a] focus:outline-none"><option>Semua Kasir</option><option>Rina S.</option><option>Dimas P.</option></select>
                        </div>
                        <div class="flex items-end">
                            <button class="w-full rounded-md bg-[#161410] px-3 py-1.5 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">Terapkan</button>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mb-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Total Transaksi</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">1,284</div>
                    </div>
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Total Omzet</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">Rp 248.5jt</div>
                    </div>
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Rata-rata / Transaksi</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">Rp 193.5rb</div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
                    <div class="border-b border-[#e2dac8] px-5 py-3.5 text-[13px] font-semibold text-[#161410]">Rincian Transaksi</div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[12.5px]">
                            <thead>
                                <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                                    <th class="px-5 py-2.5 font-semibold">Invoice</th>
                                    <th class="px-3 py-2.5 font-semibold">Tanggal</th>
                                    <th class="px-3 py-2.5 font-semibold">Kasir</th>
                                    <th class="px-3 py-2.5 font-semibold">Item</th>
                                    <th class="px-3 py-2.5 font-semibold">Total</th>
                                    <th class="px-5 py-2.5 text-right font-semibold">Metode</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                                <tr class="hover:bg-[#f7f4ee]/50"><td class="px-5 py-2.5 font-mono text-[11.5px] text-[#161410]">INV-2026-1184</td><td class="px-3 py-2.5 font-mono text-[11px] text-[#8a8073]">2026-10-31</td><td class="px-3 py-2.5">Rina S.</td><td class="px-3 py-2.5 tnum">8</td><td class="px-3 py-2.5 font-mono tnum">Rp 4.250.000</td><td class="px-5 py-2.5 text-right text-[11.5px] text-[#7a7264]">Transfer</td></tr>
                                <tr class="hover:bg-[#f7f4ee]/50"><td class="px-5 py-2.5 font-mono text-[11.5px] text-[#161410]">INV-2026-1183</td><td class="px-3 py-2.5 font-mono text-[11px] text-[#8a8073]">2026-10-31</td><td class="px-3 py-2.5">Dimas P.</td><td class="px-3 py-2.5 tnum">3</td><td class="px-3 py-2.5 font-mono tnum">Rp 1.890.000</td><td class="px-5 py-2.5 text-right text-[11.5px] text-[#7a7264]">Tunai</td></tr>
                                <tr class="hover:bg-[#f7f4ee]/50"><td class="px-5 py-2.5 font-mono text-[11.5px] text-[#161410]">INV-2026-1182</td><td class="px-3 py-2.5 font-mono text-[11px] text-[#8a8073]">2026-10-31</td><td class="px-3 py-2.5">Rina S.</td><td class="px-3 py-2.5 tnum">2</td><td class="px-3 py-2.5 font-mono tnum">Rp 750.000</td><td class="px-5 py-2.5 text-right text-[11.5px] text-[#7a7264]">QRIS</td></tr>
                                <tr class="hover:bg-[#f7f4ee]/50"><td class="px-5 py-2.5 font-mono text-[11.5px] text-[#161410]">INV-2026-1181</td><td class="px-3 py-2.5 font-mono text-[11px] text-[#8a8073]">2026-10-31</td><td class="px-3 py-2.5">Agus W.</td><td class="px-3 py-2.5 tnum">6</td><td class="px-3 py-2.5 font-mono tnum">Rp 3.120.000</td><td class="px-5 py-2.5 text-right text-[11.5px] text-[#7a7264]">Transfer</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div></section>
@endsection
