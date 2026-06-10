<?php
namespace App\Models;
use App\Models\Tasks;
use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFile extends Model
{
    protected $fillable = ['task_id', 'user_id', 'filename', 'original_name', 'mime_type', 'visibility'];
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}