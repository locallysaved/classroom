<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{       
    protected $fillable = ['class_id', 'name', 'content', 'date_added'];
    public $timestamps = false;

    public function class(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Classes::class);
    }
}
