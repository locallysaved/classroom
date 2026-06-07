<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskFile;
use App\Models\TaskSolution;
use App\Models\TaskComment;

class Tasks extends Model
{
    public $timestamps = false;
    protected $fillable = ['class_id', 'name', 'content'];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function files()
{
    return $this->hasMany(TaskFile::class, 'task_id');
}

    public function solutions()
{
    return $this->hasMany(TaskSolution::class, 'task_id');
}

public function comments()
{
    return $this->hasMany(TaskComment::class, 'task_id')->latest();
}
}