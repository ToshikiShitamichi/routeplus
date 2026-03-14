<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'roadmap_id',
        'task_code',
        'title',
        'description',
        'difficulty',
        'sort_order',
        'is_recommended',
        'is_active',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(UserTaskProgress::class);
    }
}
