<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classes extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'join_code'];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($class) {
            $class->join_code = strtoupper(Str::random(8));
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id');
    }
}