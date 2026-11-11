<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'new_tab' => 'boolean'];

    public function getHrefAttribute(): string
    {
        if (trim($this->url, '/') === 'careers') {
            return setting('careers_url', 'https://jobs.kibokogroup.com');
        }

        return preg_match('#^(https?:|mailto:|tel:)#', $this->url) ? $this->url : url($this->url);
    }

    public function isCurrent(): bool
    {
        $path = trim($this->url, '/');
        if (preg_match('#^(https?:|mailto:|tel:)#', $this->url) || $path === 'careers') {
            return false;
        }

        return $path === '' ? request()->is('/') : request()->is($path, $path.'/*');
    }
}
