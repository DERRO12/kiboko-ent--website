<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrandCategory extends Model
{
    protected $guarded = ['id'];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
