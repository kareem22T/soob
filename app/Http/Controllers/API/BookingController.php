<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Create a new booking
    public function createBooking(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
            'package_id' => 'required|exists:packages,id',
            'payment_status' => 'required|string',
            'phone' => 'required|string',
            'name' => 'required|string',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Create a new booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'offer_id' => $request->offer_id,
            'package_id' => $request->package_id,
            'phone' => $request->phone,
            'name' => $request->name,
            'note' => $request->note,
            'booking_status' => "pending",
            'payment_status' => $request->payment_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully.',
            'booking' => $booking,
        ], 201);
    }

    // Retrieve bookings for the authenticated user
    public function getBookings()
    {
        $bookings = Booking::with('offer')->forUser()->get();

        return response()->json([
            'success' => true,
            'bookings' => $bookings,
        ], 200);
    }
}
