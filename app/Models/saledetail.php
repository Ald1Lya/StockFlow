<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SaleDetail (Tabel: saledetails)
 * Menyimpan rincian tiap barang yang dibeli per transaksi (pivot item transaksi).
 */
class saledetail extends Model
{
    // Kolom-kolom yang boleh diisi
    protected $fillable = [
        'sale_id',    // ID transaksi induk (tabel sales)
        'product_id', // ID produk barang yang dibeli (tabel products)
        'quantity',   // Jumlah pcs/sak/kg yang dibeli
        'unit_price', // Harga jual produk per satuan saat transaksi terjadi
        'subtotal',   // Total harga untuk item ini (quantity * unit_price)
    ];

    /**
     * Relasi ke Product:
     * Menghubungkan detail transaksi ke data produk aslinya (nama, sku, satuan/pcs, dsb.)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}


