<?php

namespace App\Http\Controllers\API\Company;

use App\Events\ChatMessageSent;
use App\Events\MyEvent;
use App\Http\Controllers\Controller;
use App\Models\UserCustomeRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pusher\Pusher;

class RequestsController extends Controller
{
    public function get(): JsonResponse

    {
        // Fetch the user's custom requests with their associated days
        $customRequests = UserCustomeRequests::latest()->with('days', 'user')
            ->where('status', 'pending')
            ->get();

        // Return a successful response

        return response()->json([

            'success' => true,
            'custom_requests' => $customRequests,

        ], 200);
    }

}
