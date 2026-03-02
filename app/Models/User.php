<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'celular',
        'pais',
        'ciudad',
        'foto',
        'bamboo_customer_id',
        'bamboo_unique_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function guardName()
    {
        return 'web';
    }

    public function negocios()
    {
        return $this->hasMany(Negocio::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)
            ->whereIn('status', ['active', 'trial'])
            ->latest();
    }

    public function getActivePlanAttribute()
    {
        $sub = $this->subscription;
        return $sub?->plan;
    }

    /**
     * Create a 15-day free trial subscription for this user.
     */
    public function createTrialSubscription(): Subscription
    {
        return Subscription::create([
            'user_id'   => $this->id,
            'plan_id'   => null,
            'status'    => 'active',
            'is_trial'  => true,
            'starts_at' => now()->toDateString(),
            'ends_at'   => now()->addDays(15)->toDateString(),
        ]);
    }

}
