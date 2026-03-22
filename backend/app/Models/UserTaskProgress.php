<?php
// app/Models/UserTaskProgress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTaskProgress extends Model
{
    protected $fillable = [
        'user_id',
        'task_master_id',
        'status',
        'github_url',
        'deploy_url',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function taskMaster(): BelongsTo
    {
        return $this->belongsTo(TaskMaster::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
