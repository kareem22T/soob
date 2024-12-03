<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'offer_id', 'package_id', 'booking_status', 'payment_status', 'phone', 'name','note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Custom scope to filter by the authenticated user
    public function scopeForUser(Builder $query)
    {
        return $query->where('user_id', Auth::id());
    }
    // Custom scope to filter by the authenticated user
    public function scopeForCompany(Builder $query)
    {
        $companyId = Auth::guard('employee')?->id();

        return $query->whereNotNull('offer_id')
        ->whereHas('offer', function (Builder $offerQuery) use ($companyId) {
            $offerQuery->where('company_id', $companyId);
        });
    }
}
