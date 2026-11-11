<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Model;

class CoverageLocation extends Model
{
    use HasMedia;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'latitude' => 'float', 'longitude' => 'float'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('region')->orderBy('name');
    }
}
