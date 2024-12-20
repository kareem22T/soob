<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'company_ad_title',
        'company_ad_thumbnail',
        'company_ad_description',
        'company_ad_action_btn',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
