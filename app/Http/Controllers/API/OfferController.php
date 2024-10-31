<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OfferController extends Controller
{
    // Get offers by search and filter criteria
    public function getOffers(Request $request)
    {
        // Validate the incoming request parameters
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Start building the query
        $query = Offer::with('packages');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        }

        // Apply date filters
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('start_date', '>=', Carbon::parse($request->start_date));
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('end_date', '<=', Carbon::parse($request->end_date));
        }

        // Get the offers
        $offers = $query->get();

        // Filter by price if min and max price are provided
        if ($request->has('min_price') || $request->has('max_price')) {
            $offers = $offers->filter(function ($offer) use ($request) {
                return $offer->packages->filter(function ($package) use ($request) {
                    return (!$request->has('min_price') || $package->price >= $request->min_price) &&
                           (!$request->has('max_price') || $package->price <= $request->max_price);
                })->isNotEmpty();
            });
        }

        return response()->json([
            'success' => true,
            'offers' => $offers,
        ], 200);
    }
}
