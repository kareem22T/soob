<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
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

        $companies = \App\Models\Company::where('is_approved', true)->get();
        foreach ($companies as $company) {
            if ($company->logo)
                $company->logo = asset('storage/' . $company->logo);
        }

        $offers = Offer::latest()->where('status', 'Approved')->where('is_suggested', true)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfuly',
            'categories' => $categories,
            'destinations' => $destinatins,
            'events' => $events,
            'companies' => $companies,
            'offers' => $offers,
        ], 200);
    }
}
