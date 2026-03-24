<?php
// app/Models/TaskMaster.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaskMaster extends Model
{
    protected $fillable = [
        'category',
        'order',
        'level',
        'title',
        'description',
    ];

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserTaskProgress::class);
    }

    public function taskPacks(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskPack::class,
            'task_pack_items',
            'task_master_id',
            'task_pack_id'
        )->withPivot('order');
    }
}
