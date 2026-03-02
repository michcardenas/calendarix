<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'interval',
        'max_professionals',
        'price_per_additional_professional',
        'crm_ia_enabled',
        'multi_branch_enabled',
        'whatsapp_reminders',
        'email_reminders',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'crm_ia_enabled'                    => 'boolean',
        'multi_branch_enabled'              => 'boolean',
        'whatsapp_reminders'                => 'boolean',
        'email_reminders'                   => 'boolean',
        'is_active'                         => 'boolean',
        'price'                             => 'decimal:2',
        'price_per_additional_professional' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
