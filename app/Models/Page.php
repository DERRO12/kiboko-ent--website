<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasSeo, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['is_published' => 'boolean'];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function heroes(): HasMany
    {
        return $this->hasMany(HeroSection::class)->orderBy('sort_order');
    }

    public function getUrlAttribute(): string
    {
        return $this->slug === 'home' ? url('/') : url($this->slug);
    }
}
