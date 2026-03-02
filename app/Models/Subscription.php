<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_TRIAL = 'trial';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PAYMENT_FAILED = 'payment_failed';

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'is_trial',
        'bamboo_token',
        'cancelled_at',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at'    => 'date',
        'ends_at'      => 'date',
        'cancelled_at' => 'datetime',
        'is_trial'     => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function daysRemaining(): int
    {
        if (!$this->ends_at) return 0;
        return max(0, (int) now()->startOfDay()->diffInDays($this->ends_at->startOfDay(), false));
    }

    public function isTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL;
    }

    public function canBeCharged(): bool
    {
        return $this->bamboo_token
            && $this->status === self::STATUS_TRIAL
            && $this->ends_at
            && $this->ends_at->isPast();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
