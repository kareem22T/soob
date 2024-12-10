<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification(Request $request) {
        $notifications = Notification::latest()->where('receiver_id', $request->user()->id)->where('receiver_type', $request->receiver_type)->get();

        return response()->json([
            'success' => true,
            'message' => 'notifications retrieved successfully',
            'data' => $notifications
        ], 200);
    }

    public function readAll(Request $request) {
        $notifications = Notification::latest()->where('receiver_id', $request->user()->id)->where('receiver_type', $request->receiver_type)->get();

        foreach ($notifications as $notification) {
            $notification->seen = 1;
            $notification->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'notifications retrieved successfully',
            'data' => $notifications
        ], 200);
    }

}
