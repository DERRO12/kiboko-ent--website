<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->thumb_path ? asset('storage/'.$this->thumb_path) : $this->url;
    }

    public function getLabelAttribute(): string
    {
        return $this->title ?: basename($this->path);
    }
}
