<?php
namespace App\Models;
use App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $fillable = ['task_id', 'original_name', 'stored_name', 'mime_type', 'size'];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }
}