# 🏗️ StockFlow — Sistem Manajemen Inventaris & POS Toko Bangunan

**StockFlow** adalah aplikasi berbasis web untuk mengelola operasional, rantai pasok, dan transaksi kasir (*Point of Sale*) pada toko bahan bangunan. Sistem ini dirancang dengan pembagian hak akses (*Multi-Role*) yang ketat dan pencatatan mutasi stok otomatis (*Audit Trail*) dari barang masuk hingga terjual.

---

## ✨ Fitur Utama Berdasarkan Peran (Role)

### 1. 👑 Admin (Manajemen & Master Data)
* **Dashboard Operasional:** Ringkasan pendapatan bulanan, pertumbuhan transaksi, grafik tren penjualan interaktif (Bulanan & Harian), produk terlaris, serta peringatan stok menipis.
* **Manajemen Master Data:** Pengelolaan penuh (*CRUD*) untuk data **Pengguna (User)**, **Kategori**, **Brand/Merek**, **Supplier**, dan **Produk** (termasuk harga modal, harga jual, satuan, serta batas stok minimum).
* **Purchase Order (PO):** Pembuatan pesanan pembelian barang ke *supplier* dengan pembuatan nomor PO otomatis.
* **Laporan Penjualan & Inventaris:** Rekap transaksi kasir lengkap dengan filter tanggal/kasir, rincian item per nota, perhitungan **estimasi laba kotor**, serta pemantauan riwayat keluar-masuk barang (`StockMovement`).

### 2. 📦 Warehouse / Gudang (Logistik & Stok)
* **Penerimaan Barang (PO Acceptance):** Validasi penerimaan barang dari *supplier* dengan alur **satu arah (*one-way flow*)** demi menjaga integritas audit. Saat status PO diubah menjadi *Diterima*, stok produk dan harga modal (`cost_price`) diperbarui otomatis.
* **Cek Stok Gudang:** Pencarian cepat berdasarkan nama, SKU, kategori, atau brand, dilengkapi filter kondisi stok (*Menipis* & *Habis*).
* **Mutasi & Penyesuaian Stok (*Stock Opname*):** Pencatatan penyesuaian stok manual (barang rusak, selisih hitung, dll.) yang tercatat dalam riwayat mutasi stok.

### 3. 🛒 Cashier / Kasir (Point of Sale)
* **Dashboard Kasir:** Informasi ringkas omzet toko hari ini, jumlah transaksi per *shift*, dan daftar barang yang hampir habis.
* **Mesin Kasir (POS) Pintar:**
  * **Multi-Unit UI Grouping:** Produk dengan nama yang sama namun berbeda satuan jual (misalnya *Paku: Kotak vs. Kg*) digabung dalam **1 kartu produk** dengan tombol pemilih satuan instan agar layar kasir tetap bersih.
  * Keranjang belanja interaktif, kalkulasi kembalian otomatis, dan tombol nominal uang cepat.
  * Pemotongan stok dan pencatatan mutasi barang keluar (`type: out`) otomatis saat pembayaran selesai.
* **Struk Thermal & Riwayat Transaksi:** Cetak struk otomatis setelah transaksi berhasil serta pencarian dan cetak ulang nota lama.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel 13 (PHP 8.5)
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
* **Database:** MySQL / MariaDB
* **Arsitektur & Keamanan:** Role-Based Access Control (Middleware), Database Transactions (`DB::transaction`) dengan *Pessimistic Locking* (`lockForUpdate`) untuk mencegah selisih stok saat transaksi bersamaan.

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah berikut untuk menjalankan proyek ini di komputer lokal:

1. **Clone repositori ini**
   ```bash
   git clone https://github.com/username/stockflow-toko-bangunan.git
   cd stockflow-toko-bangunan
   ```

2. **Install dependensi PHP & JavaScript**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi file environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan pengaturan koneksi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) di dalam file `.env`.

4. **Jalankan migrasi database**
   ```bash
   php artisan migrate
   ```

5. **Compile aset frontend & jalankan server**
   ```bash
   npm run build
   php artisan serve
   ```
   Buka browser dan akses `http://127.0.0.1:8000`.

---

## 📂 Struktur Modul & Routing

```text
├── admin/
│   ├── dashboard          # Ringkasan & grafik tren pendapatan
│   ├── users              # Manajemen akun Admin, Gudang, Kasir
│   ├── products           # Master data produk & satuan
│   ├── purchases          # Pembuatan Purchase Order (PO)
│   ├── report_sales       # Laporan omzet & laba kotor
│   └── report_inventorys  # Laporan stok & riwayat mutasi
├── warehouse/
│   ├── dashboard          # Ringkasan aktivitas gudang
│   ├── iventorys          # Pemantauan stok fisik gudang
│   ├── acceptpos          # Penerimaan barang dari PO
│   └── stockmovements     # Pencatatan mutasi / opname stok
└── cashier/
    ├── dashboard          # Ringkasan transaksi kasir
    ├── pos                # Transaksi penjualan & cetak nota
    └── histories          # Riwayat transaksi & cetak ulang struk
```