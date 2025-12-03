<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransmutationRecipe extends Model
{
    protected $guarded = [];

    protected $casts = [
        'input_quantity' => 'integer',
        'output_quantity' => 'integer',
    ];
}
