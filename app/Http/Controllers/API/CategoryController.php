<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(Request $request) {
        $categories = \App\Models\Category::all();

        foreach ($categories as $cat) {
            $cat->icon = asset('storage/' . $cat->icon);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Fetched successfuly',
            'categories' => $categories,
        ], 200);
    }
}
