<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['offer_id', 'title', 'description', 'price', 'discounted_price', 'image_path'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
