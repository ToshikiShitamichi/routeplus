<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMaster extends Model
{
    protected $fillable = [
        'category',
        'order',
        'level',
        'title',
        'description',
        'is_official',
        'visibility',
        'organization_id',
        'created_by',
    ];

    protected $casts = [
        'is_official' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function progress()
    {
        return $this->hasMany(UserTaskProgress::class);
    }
}
