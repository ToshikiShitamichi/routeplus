<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTaskPack extends Model
{
    protected $fillable = ['group_id', 'task_pack_id'];

    public function taskPack()
    {
        return $this->belongsTo(TaskPack::class);
    }
}
