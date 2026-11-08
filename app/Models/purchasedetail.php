<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class purchasedetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_cost',
        'subtotal',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(product::class);
    }
}
