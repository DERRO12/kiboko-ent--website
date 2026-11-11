<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    public const TYPES = [
        'intro' => 'Intro text (heading, text, button)',
        'stats' => 'Statistics strip (variant = statistic group)',
        'services_cards' => 'Service cards for homepage (variant = how many)',
        'services_grid' => 'All services grid',
        'brands_carousel' => 'Featured brands carousel',
        'brands_tabs' => 'Partners / own brands tabs',
        'story' => 'Our story (text, image, milestones)',
        'values' => 'Purpose, vision and values cards',
        'reach_map' => 'Coverage map and locations',
        'insights_list' => 'Insights list with category filters',
        'contact' => 'Contact details and form',
        'text_image' => 'Text with image',
        'cta' => 'Call-to-action banner',
    ];

    protected $guarded = ['id'];

    protected $casts = ['is_visible' => 'boolean'];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function background(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'background_media_id');
    }
}
