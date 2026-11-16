<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Sale (Tabel: sales)
 * Menyimpan data utama transaksi penjualan/struk kasir.
 */
class sale extends Model
{
    // Kolom-kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'user_id',        // ID kasir yang memproses transaksi
        'invoice_number', // Nomor struk/nota unik (contoh: INV-20261116-001)
        'total_amount',   // Total tagihan belanja keseluruhan
        'cash_tendered',  // Uang tunai yang dibayarkan pelanggan
        'change_amount',  // Uang kembalian untuk pelanggan
        'sale_date',      // Waktu transaksi berlangsung
    ];

    /**
     * Relasi ke User (Kasir):
     * Setiap transaksi penjualan dimiliki oleh 1 kasir.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke SaleDetail (Rincian Barang):
     * 1 transaksi penjualan bisa memiliki banyak item/barang yang dibeli.
     */
    public function details(): HasMany
    {
        return $this->hasMany(saledetail::class, 'sale_id');
    }
}
