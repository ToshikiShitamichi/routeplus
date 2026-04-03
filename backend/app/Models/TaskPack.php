<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaskPack extends Model
{
    protected $fillable = [
        'organization_id',
        'created_by',
        'name',
        'description',
        'is_official',
        'is_public',
    ];

    protected $casts = [
        'is_official' => 'boolean',
        'is_public'   => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TaskPackItem::class)->orderBy('order');
    }

    public function taskMasters(): BelongsToMany
    {
        return $this->belongsToMany(
            TaskMaster::class,
            'task_pack_items',
            'task_pack_id',
            'task_master_id'
        )->withPivot('id', 'order')->orderBy('task_pack_items.order');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'group_task_packs',
            'task_pack_id',
            'group_id'
        )->withTimestamps();
    }
}
