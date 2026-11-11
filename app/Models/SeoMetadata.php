<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    use HasMedia;

    protected $table = 'seo_metadata';

    protected $guarded = ['id'];

    protected $casts = ['noindex' => 'boolean'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getOwnerLabelAttribute(): string
    {
        $o = $this->seoable;

        return class_basename($this->seoable_type).': '.($o->title ?? $o->name ?? '#'.$this->seoable_id);
    }
}
