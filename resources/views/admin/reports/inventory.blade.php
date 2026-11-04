@extends('layouts.admin-layout')

@section('page-title', 'Laporan Inventori')

@section('content')
<section><div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#a67b3f]">Laporan</div>
                        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#161410]">Laporan Inventori</h1>
                        <p class="mt-1 text-[13px] text-[#7a7264]">Nilai aset & perputaran stok per gudang</p>
                    </div>
                    <button class="inline-flex items-center gap-1.5 rounded-md bg-[#161410] px-3 py-2 text-[12.5px] font-semibold text-[#ece7dd] hover:bg-[#2a2520]">
                        <svg class="h-3.5 w-3.5 text-[#c9934a]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Export
                    </button>
                </div>

                <div class="mb-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Total Nilai Aset</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">Rp 1.42 M</div>
                    </div>
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Total SKU</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">342</div>
                    </div>
                    <div class="rounded-lg border border-[#e2dac8] bg-white p-4">
                        <div class="text-[11px] uppercase tracking-wider text-[#8a8073]">Turnover Rate</div>
                        <div class="mt-1.5 font-mono text-xl font-semibold tnum text-[#161410]">4.2x</div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-[#e2dac8] bg-white">
                    <div class="border-b border-[#e2dac8] px-5 py-3.5 text-[13px] font-semibold text-[#161410]">Perputaran Stok per Gudang</div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-[12.5px]">
                            <thead>
                                <tr class="border-b border-[#e2dac8] bg-[#f7f4ee]/60 text-[10.5px] uppercase tracking-wider text-[#8a8073]">
                                    <th class="px-5 py-2.5 font-semibold">Gudang</th>
                                    <th class="px-3 py-2.5 font-semibold">Total SKU</th>
                                    <th class="px-3 py-2.5 font-semibold">Nilai Aset</th>
                                    <th class="px-3 py-2.5 font-semibold">Masuk</th>
                                    <th class="px-3 py-2.5 font-semibold">Keluar</th>
                                    <th class="px-5 py-2.5 text-right font-semibold">Utilisasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2dac8]/70 text-[#3a352d]">
                                <tr class="hover:bg-[#f7f4ee]/50">
                                    <td class="px-5 py-2.5 font-medium">Gudang Utama</td>
                                    <td class="px-3 py-2.5 font-mono tnum">186</td>
                                    <td class="px-3 py-2.5 font-mono tnum">Rp 812.4jt</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#4a6a4a]">+1,240</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#a04a3a]">-1,108</td>
                                    <td class="px-5 py-2.5 text-right"><div class="inline-flex items-center gap-1.5"><div class="h-1.5 w-16 overflow-hidden rounded-full bg-[#e2dac8]"><div class="h-full w-[72%] bg-[#c9934a]"></div></div><span class="font-mono text-[11px] tnum">72%</span></div></td>
                                </tr>
                                <tr class="hover:bg-[#f7f4ee]/50">
                                    <td class="px-5 py-2.5 font-medium">Gudang Barat</td>
                                    <td class="px-3 py-2.5 font-mono tnum">92</td>
                                    <td class="px-3 py-2.5 font-mono tnum">Rp 402.8jt</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#4a6a4a]">+680</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#a04a3a]">-512</td>
                                    <td class="px-5 py-2.5 text-right"><div class="inline-flex items-center gap-1.5"><div class="h-1.5 w-16 overflow-hidden rounded-full bg-[#e2dac8]"><div class="h-full w-[48%] bg-[#c9934a]"></div></div><span class="font-mono text-[11px] tnum">48%</span></div></td>
                                </tr>
                                <tr class="hover:bg-[#f7f4ee]/50">
                                    <td class="px-5 py-2.5 font-medium">Gudang Timur</td>
                                    <td class="px-3 py-2.5 font-mono tnum">64</td>
                                    <td class="px-3 py-2.5 font-mono tnum">Rp 205.2jt</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#4a6a4a]">+320</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#a04a3a]">-410</td>
                                    <td class="px-5 py-2.5 text-right"><div class="inline-flex items-center gap-1.5"><div class="h-1.5 w-16 overflow-hidden rounded-full bg-[#e2dac8]"><div class="h-full w-[35%] bg-[#c9934a]"></div></div><span class="font-mono text-[11px] tnum">35%</span></div></td>
                                </tr>
                                <tr class="hover:bg-[#f7f4ee]/50">
                                    <td class="px-5 py-2.5 font-medium">Gudang Transit</td>
                                    <td class="px-3 py-2.5 font-mono tnum">-</td>
                                    <td class="px-3 py-2.5 font-mono tnum">Rp 0</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#4a6a4a]">+0</td>
                                    <td class="px-3 py-2.5 font-mono tnum text-[#a04a3a]">-0</td>
                                    <td class="px-5 py-2.5 text-right"><div class="inline-flex items-center gap-1.5"><div class="h-1.5 w-16 overflow-hidden rounded-full bg-[#e2dac8]"><div class="h-full w-[0%] bg-[#c9934a]"></div></div><span class="font-mono text-[11px] tnum">0%</span></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div></section>
@endsection
