<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskFile;
use App\Models\TaskSolution;
use App\Models\TaskComment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tasks extends Model
{
    public $timestamps = false;
    protected $fillable = ['class_id', 'name', 'content', 'date_added', 'due_date'];
    protected $casts = [
        'due_date'   => 'date',
        'date_added' => 'datetime',
];

public function comments(): HasMany
{
    return $this->hasMany(TaskComment::class, 'task_id');
}

public function solutions(): HasMany
{
    return $this->hasMany(TaskSolution::class, 'task_id');
}

public function files(): HasMany
{
    return $this->hasMany(TaskFile::class, 'task_id');
}

public function class(): BelongsTo
{
    return $this->belongsTo(Classes::class, 'class_id');
}
}