<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BambooPaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'action',
        'request_payload',
        'response_payload',
        'http_status',
        'success',
        'error_message',
    ];

    protected $casts = [
        'request_payload'  => 'array',
        'response_payload' => 'array',
        'success'          => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
