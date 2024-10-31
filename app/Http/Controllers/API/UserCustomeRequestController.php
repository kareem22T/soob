<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserCustomeRequests;
use App\Models\Day;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserCustomeRequestController extends Controller
{
    public function get(Request $request): JsonResponse

    {

        // Assuming user_id is passed as a query parameter

        $userId = $request->user()->id;

        // Validate that user_id is provided

        if (!$userId) {
            return response()->json([

                'success' => false,
                'message' => 'User  ID is required.',

            ], 400);
        }

        // Fetch the user's custom requests with their associated days
        $customRequests = UserCustomeRequests::with('days')
            ->where('user_id', $userId)
            ->get();

        // Return a successful response

        return response()->json([

            'success' => true,
            'custom_requests' => $customRequests,

        ], 200);
    }
    public function store(Request $request): JsonResponse
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'destination' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array',
            'days.*.description' => 'required|string',
            'days.*.day' => 'required|date',
        ]);

        $validatedData['user_id'] = $request->user()->id;

        // Create a new booking request
        $booking = UserCustomeRequests::create($validatedData);

        // Loop through the days and create Day records
        foreach ($validatedData['days'] as $day) {
            $booking->days()->create($day);
        }

        // Return a successful response
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully.',
            'booking' => $booking->load('days'), // Include the days in the response
        ], 201);
    }

    public function cancel($id, Request $request): JsonResponse
    {
        // Find the booking request by ID
        $booking = UserCustomeRequests::where('user_id', $request->user()->id)->with('days')->find($id);

        // Check if the booking exists
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.',
            ], 404);
        }

        // Delete associated days
        $booking->days()->delete();

        // Delete the booking request
        $booking->delete();

        // Return a successful response
        return response()->json([
            'success' => true,
            'message' => 'Booking canceled successfully.',
        ], 200);
    }
}
