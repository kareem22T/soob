<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $settings = \App\Models\UserSetting::first();
        $settings->company_ad_thumbnail = asset('storage/' . $settings->company_ad_thumbnail);

        $categories = \App\Models\Category::all();
        foreach ($categories as $cat) {
            $cat->icon = asset('storage/' . $cat->icon);
        }

        $destinatins = \App\Models\Destination::all();
        foreach ($destinatins as $destinatin) {
            $destinatin->thumbnail = asset('storage/' . $destinatin->thumbnail);
            $destinatin->cover = asset('storage/' . $destinatin->cover);
        }

        $events = \App\Models\Event::all();
        foreach ($events as $event) {
            $event->banner = asset('storage/' . $event->banner);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfuly',
            'categories' => $categories,
            'destinations' => $destinatins,
            'events' => $events,
        ], 200);
    }
}
