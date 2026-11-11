<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasMedia;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];
}
