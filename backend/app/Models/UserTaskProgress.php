<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTaskProgress extends Model
{
    protected $table = 'user_task_progress';

    protected $fillable = [
        'user_id',
        'task_id',
        'status',
        'understanding_level',
        'submitted_at',
        'reviewed_at',
        'memo',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}