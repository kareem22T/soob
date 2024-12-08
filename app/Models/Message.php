<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'msg',
        'sender_id',
        'sender_type',
        'msg_type',
        'msg_reference_id',
        'seen',
        'chat_id'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function offer()
    {
        return $this->belongsTo(RequestOffers::class, 'msg_reference_id')
            ->where('msg_type', 'offer');
    }
}
