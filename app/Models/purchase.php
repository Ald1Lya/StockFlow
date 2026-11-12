<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Relations\HasMany;
class purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'user_id',
        'po_number',
        'status',
        'total_amount',
        'purchase_date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(purchasedetail::class, 'purchase_id');
    }
}
