<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    public function tasks(): HasMany
{
    return $this->hasMany(Task::class);
}
}
