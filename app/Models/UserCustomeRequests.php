<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserCustomeRequests extends Model
{
    protected $fillable = ['destination', 'description',  'start_date', 'end_date', 'user_id'];

    protected $casts = ['images'=> 'array'];

    public function days()
    {
        return $this->hasMany(Day::class, 'user_custome_request_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function offers()
    {
        return $this->hasMany(RequestOffers::class, 'request_id');
    }
    public function scopeForUser(Builder $query)
    {
        return $query->where('user_id', Auth::id());
    }

}
