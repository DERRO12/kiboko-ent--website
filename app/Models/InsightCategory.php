<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsightCategory extends Model
{
    protected $guarded = ['id'];

    public function insights(): HasMany
    {
        return $this->hasMany(Insight::class);
    }
}
