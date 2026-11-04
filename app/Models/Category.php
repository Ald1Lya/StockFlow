<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'code', 'description'];

    public function products(): HasMany
    {
        return $this->hasMany(product::class, 'category_id');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
