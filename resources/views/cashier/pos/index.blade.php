@extends('layouts.cashier-layout')

@section('page-title', 'Belanja (POS)')

@section('content')
<div x-data="posApp({{ Js::from($groupedProducts) }}, {{ $latestReceipt ? 'true' : 'false' }})" class="flex-1 flex flex-col">

    <header class="bg-white border-b border-[#e2dac8] h-16 flex items-center justify-between px-8 sticky top-0 z-10 print:hidden">
        <div class="flex items-center gap-3">
            <h1 class="text-[16px] font-semibold tracking-tight">Transaksi Penjualan (POS)</h1>
            @if ($latestReceipt)
                <button type="button" @click="showReceiptModal = true" class="rounded-md bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                    Lihat Nota Terakhir ({{ $latestReceipt->invoice_number }})
                </button>
            @endif
        </div>
        <button type="button" @click="resetCart()" class="px-3.5 py-1.5 bg-white border border-[#e2dac8] rounded-lg text-xs font-medium hover:bg-[#f7f4ee]">
            Reset Keranjang
        </button>
    </header>

    <div class="p-6 space-y-4 print:hidden">
        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-3.5 text-sm font-medium text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-120px)]">
            <!-- Katalog Produk (1 Card = 1 Nama Barang, Bisa Ganti Satuan) -->
            <div class="lg:col-span-7 xl:col-span-8 bg-white border border-[#e2dac8] rounded-xl p-5 flex flex-col h-full shadow-sm">
                <div class="flex gap-3 mb-4">
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Scan SKU / Cari nama barang (Semen, Besi, Paku)..."
                        class="flex-1 border border-[#e2dac8] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#c9934a] focus:ring-1 focus:ring-[#c9934a]"
                    >
                    <select
                        x-model="selectedCategory"
                        class="border border-[#e2dac8] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#c9934a] bg-white"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto pr-1 content-start">
                    <template x-for="group in filteredProducts" :key="group.name">
                        <div
                            x-data="{ selectedIdx: 0 }"
                            class="flex flex-col justify-between text-left border border-[#e2dac8] rounded-xl hover:border-[#c9934a] hover:shadow-sm transition-all bg-white overflow-hidden group/card"
                        >
                            <!-- Thumbnail Gambar Produk -->
                            <div class="relative w-full h-28 bg-[#f7f4ee] flex items-center justify-center overflow-hidden border-b border-[#e2dac8]/60">
                                <template x-if="group.variants[selectedIdx].image_url || group.image_url">
                                    <img
                                        :src="group.variants[selectedIdx].image_url || group.image_url"
                                        :alt="group.name"
                                        class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-200"
                                    >
                                </template>
                                <template x-if="!(group.variants[selectedIdx].image_url || group.image_url)">
                                    <div class="flex flex-col items-center justify-center text-[#b8ae9b]">
                                        <svg class="w-8 h-8 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>
                                        <span class="text-[10px] mt-1 font-medium">No Image</span>
                                    </div>
                                </template>

                                <!-- Badge Stok Mengambang di atas gambar -->
                                <span
                                    :class="group.variants[selectedIdx].stock > 0 ? 'bg-green-600 text-white' : 'bg-red-600 text-white'"
                                    class="absolute top-2 right-2 text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm whitespace-nowrap"
                                    x-text="group.variants[selectedIdx].stock + ' ' + group.variants[selectedIdx].unit"
                                ></span>
                            </div>

                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-[10px] text-gray-400 font-mono truncate mb-0.5" x-text="group.variants[selectedIdx].sku"></div>
                                    <div class="font-bold text-[13px] leading-snug line-clamp-2 mb-2" x-text="group.name" :title="group.name"></div>

                                    <!-- Tombol Switcher Satuan (Muncul jika > 1 satuan, misal Kotak vs Kg) -->
                                    <div class="flex flex-wrap gap-1 mb-2.5" x-show="group.variants.length > 1">
                                        <template x-for="(v, vIdx) in group.variants" :key="v.id">
                                            <button
                                                type="button"
                                                @click.stop="selectedIdx = vIdx"
                                                :class="selectedIdx === vIdx ? 'bg-[#161410] text-[#c9934a] border-[#161410]' : 'bg-[#f7f4ee] text-[#7a7264] border-[#e2dac8] hover:border-[#c9934a]'"
                                                class="border px-2 py-0.5 rounded text-[10px] font-semibold transition-colors"
                                                x-text="v.unit"
                                            ></button>
                                        </template>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-[#e2dac8]/60 flex items-center justify-between gap-2 mt-auto">
                                    <div class="font-mono font-bold text-[13.5px] text-[#161410]" x-text="formatRupiah(group.variants[selectedIdx].price)"></div>
                                    <button
                                        type="button"
                                        @click="addToCart(group.name, group.variants[selectedIdx])"
                                        :disabled="group.variants[selectedIdx].stock <= 0"
                                        class="bg-[#161410] text-[#c9934a] disabled:bg-gray-200 disabled:text-gray-400 px-2.5 py-1.5 rounded-lg text-[11px] font-bold hover:bg-black transition-colors"
                                    >
                                        + Pilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Keranjang & Pembayaran -->
            <form action="{{ route('cashier.pos.store') }}" method="POST" class="lg:col-span-5 xl:col-span-4 bg-white border border-[#e2dac8] rounded-xl flex flex-col h-full shadow-sm">
                @csrf
                <div class="p-4 border-b border-[#e2dac8] bg-[#f7f4ee]/60 rounded-t-xl flex justify-between items-center">
                    <h3 class="font-bold text-[14.5px]">Keranjang Belanja</h3>
                    <span class="bg-[#161410] text-white text-[11px] font-bold px-2 py-0.5 rounded-full" x-text="cart.length + ' Item'"></span>
                </div>

                <!-- List Item -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex items-center justify-center text-center text-xs text-[#8a8073]">
                            Keranjang masih kosong.<br>Pilih produk di sebelah kiri untuk memulai.
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.product_id">
                        <div class="flex justify-between items-start border-b border-[#e2dac8]/50 pb-3">
                            <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                            <input type="hidden" :name="'items[' + index + '][quantity]'" :value="item.quantity">

                            <div class="flex-1 pr-2">
                                <div class="font-bold text-[13px]" x-text="item.name + ' (' + item.unit + ')'"></div>
                                <div class="text-[11px] text-[#7a7264] font-mono mt-0.5" x-text="formatRupiah(item.price)"></div>

                                <div class="inline-flex items-center gap-2 mt-1.5 border border-[#e2dac8] rounded-md px-1.5 py-0.5 bg-[#f7f4ee]/50">
                                    <button type="button" @click="updateQty(index, -1)" class="px-1.5 font-bold text-gray-600 hover:text-black">-</button>
                                    <span class="font-mono text-xs font-bold min-w-[20px] text-center" x-text="item.quantity"></span>
                                    <button type="button" @click="updateQty(index, 1)" class="px-1.5 font-bold text-gray-600 hover:text-black">+</button>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="font-mono font-bold text-[13.5px]" x-text="formatRupiah(item.price * item.quantity)"></div>
                                <button type="button" @click="removeItem(index)" class="text-[11px] text-red-500 hover:underline mt-1">Hapus</button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Form Pembayaran -->
                <div class="p-4 border-t border-[#e2dac8] bg-[#f7f4ee]/30 rounded-b-xl space-y-3">
                    <div class="flex justify-between items-end border-b border-[#e2dac8] pb-3">
                        <span class="text-[12px] font-semibold text-[#7a7264]">TOTAL TAGIHAN</span>
                        <span class="font-mono text-[22px] font-bold text-[#161410] leading-none" x-text="formatRupiah(totalAmount)"></span>
                    </div>

                    <div>
                        <label class="block text-[11.5px] font-semibold mb-1 text-[#7a7264]">Uang Tunai</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500 font-mono text-sm">Rp</span>
                            <input
                                type="number"
                                name="cash_tendered"
                                x-model.number="cashTendered"
                                required
                                min="0"
                                class="w-full border border-[#e2dac8] rounded-lg pl-10 pr-4 py-2 font-mono font-bold text-[16px] focus:border-[#c9934a] focus:outline-none focus:ring-1 focus:ring-[#c9934a]"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" @click="cashTendered = totalAmount" class="bg-white border border-[#e2dac8] py-1 rounded text-[11px] font-medium hover:bg-[#f7f4ee]">Pas</button>
                        <button type="button" @click="cashTendered = 100000" class="bg-white border border-[#e2dac8] py-1 rounded text-[11px] font-medium hover:bg-[#f7f4ee]">100rb</button>
                        <button type="button" @click="cashTendered = 200000" class="bg-white border border-[#e2dac8] py-1 rounded text-[11px] font-medium hover:bg-[#f7f4ee]">200rb</button>
                        <button type="button" @click="cashTendered = 500000" class="bg-white border border-[#e2dac8] py-1 rounded text-[11px] font-medium hover:bg-[#f7f4ee]">500rb</button>
                    </div>

                    <div class="flex justify-between items-center bg-green-50 p-2.5 rounded-lg border border-green-200">
                        <span class="text-[11.5px] font-bold text-green-800">KEMBALIAN</span>
                        <span class="font-mono text-[16px] font-bold text-green-700" x-text="formatRupiah(changeAmount)"></span>
                    </div>

                    <button
                        type="submit"
                        :disabled="cart.length === 0 || cashTendered < totalAmount"
                        class="w-full bg-[#161410] text-[#c9934a] disabled:bg-gray-300 disabled:text-gray-500 font-bold text-[14px] py-3 rounded-xl hover:bg-black transition-colors shadow"
                    >
                        Bayar & Cetak Nota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODAL NOTA / STRUK OTOMATIS MUNCUL SETELAH BAYAR BERHASIL  -->
    <!-- ========================================================== -->
    @if ($latestReceipt)
    <div x-show="showReceiptModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 print:static print:bg-white print:p-0">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-2xl print:shadow-none print:max-w-full">
            <!-- Header Modal (Disembunyikan saat Print) -->
            <div class="mb-4 flex items-center justify-between border-b border-[#e2dac8] pb-3 print:hidden">
                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-800">Transaksi Berhasil</span>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="rounded-lg bg-[#161410] px-3 py-1.5 text-xs font-semibold text-[#c9934a] hover:bg-black">
                        Cetak Struk
                    </button>
                    <button type="button" @click="showReceiptModal = false" class="rounded-lg border border-[#e2dac8] px-3 py-1.5 text-xs font-medium text-[#7a7264] hover:text-black">
                        Tutup
                    </button>
                </div>
            </div>

            <!-- Isi Struk Thermal -->
            <div class="font-mono text-[12px] text-[#161410]">
                <div class="text-center border-b-2 border-dashed border-gray-300 pb-4 mb-4">
                    <h3 class="font-bold text-[17px] tracking-wide">TOKO BANGUNAN</h3>
                    <p class="text-[11px] text-[#7a7264]">Nota Penjualan Resmi</p>
                </div>

                <div class="space-y-1 mb-4 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-[#7a7264]">No. Invoice :</span>
                        <span class="font-bold">{{ $latestReceipt->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#7a7264]">Tanggal :</span>
                        <span>{{ \Carbon\Carbon::parse($latestReceipt->sale_date)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#7a7264]">Kasir :</span>
                        <span>{{ $latestReceipt->user?->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="border-t border-b border-dashed border-gray-300 py-3 mb-3 space-y-2.5">
                    @foreach ($latestReceipt->details as $detail)
                        <div>
                            <div class="font-bold">{{ $detail->product?->name ?? 'Produk' }}</div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-[#7a7264]">{{ $detail->quantity }} {{ $detail->product?->pcs ?? '' }} x {{ number_format($detail->unit_price, 0, ',', '.') }}</span>
                                <span class="font-bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between font-bold text-[14px]">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($latestReceipt->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#7a7264]">
                        <span>Tunai</span>
                        <span>Rp {{ number_format($latestReceipt->cash_tendered, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-green-700 border-t border-dashed border-gray-300 pt-2">
                        <span>KEMBALI</span>
                        <span>Rp {{ number_format($latestReceipt->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 text-center text-[10px] text-[#7a7264] border-t border-dashed border-gray-300 pt-3">
                    <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
                    <p class="font-bold mt-1 text-[#161410]">TERIMA KASIH</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function posApp(products, hasReceipt) {
    return {
        products: products,
        search: '',
        selectedCategory: '',
        cart: [],
        cashTendered: 0,
        showReceiptModal: hasReceipt,

        get filteredProducts() {
            return this.products.filter(group => {
                const matchCategory = !this.selectedCategory || String(group.category_id) === String(this.selectedCategory);
                const keyword = this.search.toLowerCase().trim();
                const matchSearch = !keyword
                    || group.name.toLowerCase().includes(keyword)
                    || group.variants.some(v => v.sku.toLowerCase().includes(keyword));
                return matchCategory && matchSearch;
            });
        },

        addToCart(name, variant) {
            if (variant.stock <= 0) return;

            const existing = this.cart.find(item => item.product_id === variant.id);
            if (existing) {
                if (existing.quantity < variant.stock) {
                    existing.quantity++;
                } else {
                    alert('Stok maksimal tercapai (' + variant.stock + ' ' + variant.unit + ')');
                }
            } else {
                this.cart.push({
                    product_id: variant.id,
                    name: name,
                    unit: variant.unit,
                    price: variant.price,
                    stock: variant.stock,
                    quantity: 1
                });
            }
        },

        updateQty(index, delta) {
            const item = this.cart[index];
            const nextQty = item.quantity + delta;
            if (nextQty <= 0) {
                this.cart.splice(index, 1);
            } else if (nextQty <= item.stock) {
                item.quantity = nextQty;
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        resetCart() {
            this.cart = [];
            this.cashTendered = 0;
        },

        get totalAmount() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get changeAmount() {
            return Math.max(0, (this.cashTendered || 0) - this.totalAmount);
        },

        formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number || 0);
        }
    }
}
</script>
@endsection