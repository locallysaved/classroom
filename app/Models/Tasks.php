<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    public function class(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Classes::class);
    }
}
