<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function getDestinations(Request $request) {
        $destinatins = \App\Models\Destination::all();

        foreach ($destinatins as $destinatin) {
            $destinatin->thumbnail = asset('storage/' . $destinatin->thumbnail);
            $destinatin->cover = asset('storage/' . $destinatin->cover);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfuly',
            'destinations' => $destinatins,
        ], 200);
    }
}
