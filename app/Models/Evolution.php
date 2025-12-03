<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolution extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'required_materials' => 'array',
        'stat_modifiers' => 'array',
    ];
}
