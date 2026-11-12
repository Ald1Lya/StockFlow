<?php

namespace App\Models;


use App\Models\stockmovement;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'description',
        'image',
        'cost_price',
        'selling_price',
        'stock',
        'min_stock',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

        public function latestStockMovement(): HasOne
    {
        return $this->hasOne(stockmovement::class)->latestOfMany();
    }
}
