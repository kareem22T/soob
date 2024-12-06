<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_phone_verified',
        'is_approved',
        'password',
        'verification_code',
        'current_code_expired_at',
        'is_rejected',
        'rejection_reason',
        'company_id',
        'member_role',
    ];

    public function scopeForCompany(Builder $query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
