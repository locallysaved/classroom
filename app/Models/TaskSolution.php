<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSolution extends Model
{
    protected $fillable = ['task_id', 'user_id', 'original_name', 'stored_name', 'mime_type', 'size'];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}