<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskPackItem extends Model
{
    protected $fillable = [
        'task_pack_id',
        'task_master_id',
        'order',
    ];

    public function taskPack(): BelongsTo
    {
        return $this->belongsTo(TaskPack::class);
    }

    public function taskMaster(): BelongsTo
    {
        return $this->belongsTo(TaskMaster::class);
    }
}
