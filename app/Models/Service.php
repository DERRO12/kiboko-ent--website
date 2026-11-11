<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, HasMedia, HasSeo, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean', 'show_on_home' => 'boolean'];
}
