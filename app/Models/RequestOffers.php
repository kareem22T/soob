<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestOffers extends Model
{
    protected $fillable = [
        'request_id',
        'status',
        'offer_details',
        'offer_price'
    ];

    public function request()
    {
        return $this->belongsTo(UserCustomeRequests::class, 'request_id');
    }
}
