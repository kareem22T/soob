<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Offer extends Model
{
    protected $fillable = ['title', 'description', 'is_suggested', 'images', 'company_id','category_id', 'start_date', 'end_date', 'status'];

    protected $casts = ['images'=> 'array'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function scopeForCompany(Builder $query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
