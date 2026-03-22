<?php
// app/Models/TaskMaster.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
