<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'user_id',
        'due_date',
    ];

    protected $casts = [
        'priority' => TaskPriority::class,
        'status' => TaskStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $task) {
            $task->user()->associate(Auth::user());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
