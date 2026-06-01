<?php

namespace App\Models;

use App\Models\Tasks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'class_id');
    }
}