<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class stockmovement extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'approved_note',
        

    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(product::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
}

