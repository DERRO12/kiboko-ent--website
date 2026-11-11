<?php

namespace App\Models\Concerns;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::created(fn ($m) => $m->seo()->firstOrCreate([]));
        static::deleted(function ($m) {
            if (! method_exists($m, 'isForceDeleting') || $m->isForceDeleting()) {
                $m->seo()->delete();
            }
        });
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }
}
