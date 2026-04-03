<?php
// app/Models/Invitation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'organization_id',
        'group_id',
        'invited_by',
        'token',
        'label',
        'max_uses',
        'used_count',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // 有効な招待かチェック
    public function isValid(): bool
    {
        // 期限切れはNG
        if ($this->expires_at->isPast()) return false;

        // max_uses が 0 なら無制限
        if ($this->max_uses === 0) return true;

        // 使用回数が上限未満ならOK
        return $this->used_count < $this->max_uses;
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
