<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [ 'name', 'code', 'description'];



    public function products(): HasMany
    {
        return $this->hasMany(product::class);
    }
}
