<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id'
    ];

    // Relationship with Message model
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
