<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'status',
        'reference_id',
        'receiver_type',
        'receiver_id',
        'seen',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'reference_id');
    }
}
