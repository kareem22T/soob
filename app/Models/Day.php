<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $fillable = ['user_custome_request_id', 'description', 'day'];

    public function request()
    {
        return $this->belongsTo(UserCustomeRequests::class, 'id', 'user_custome_request_id');
    }

}
