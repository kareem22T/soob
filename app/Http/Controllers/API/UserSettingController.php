<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    public function getUserSettings() {
        $settings = \App\Models\UserSetting::first();

        $settings->company_ad_thumbnail = asset('storage/' . $settings->company_ad_thumbnail);
        $settings->custom_ad = asset('storage/' . $settings->custom_ad);

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfuly',
            'settings' => $settings,
        ], 200);
    }
}
