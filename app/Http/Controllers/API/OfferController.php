<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    $query = Offer::latest()->with('packages');

    // Check if the user is an Employee
    if ($request->user() && $request->user() instanceof Employee) {
        $query->where('company_id', $request->user()->company_id);
    }

    // Apply search filter
    if ($request->has('search') && !empty($request->search)) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'LIKE', '%' . $request->search . '%')
              ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        });
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'images' => 'required|array',
                'images.*' => 'file|image|max:2048',
                'packages' => 'nullable|array',
                'packages.*.image_path' => 'required|file|image|max:2048',
                'packages.*.title' => 'required|string|max:255',
                'packages.*.description' => 'required|string',
                'packages.*.price' => 'required|numeric',
                'packages.*.discounted_price' => 'nullable|numeric',
            ], [
                'title.required' => 'عنوان العرض مطلوب.',
                'title.string' => 'عنوان العرض يجب أن يكون نصاً.',
                'title.max' => 'عنوان العرض يجب ألا يتجاوز 255 حرفاً.',
                'description.required' => 'الوصف مطلوب.',
                'description.string' => 'الوصف يجب أن يكون نصاً.',
                'start_date.required' => 'تاريخ البدء مطلوب.',
                'start_date.date' => 'تاريخ البدء يجب أن يكون تاريخاً صالحاً.',
                'end_date.required' => 'تاريخ الانتهاء مطلوب.',
                'end_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخاً صالحاً.',
                'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون مساوياً أو لاحقاً لتاريخ البدء.',
                'images.required' => 'يجب إضافة صور.',
                'images.array' => 'الصور يجب أن تكون مجموعة.',
                'images.*.file' => 'كل صورة يجب أن تكون ملفاً.',
                'images.*.image' => 'كل صورة يجب أن تكون ملف صورة صالح.',
                'images.*.max' => 'كل صورة يجب ألا تتجاوز 2 ميجابايت.',
                'packages.array' => 'الباقات يجب أن تكون مجموعة.',
                'packages.*.image_path.required' => 'يجب إدخال صورة لكل باقة.',
                'packages.*.image_path.file' => 'صورة الباقة يجب أن تكون ملفاً.',
                'packages.*.image_path.image' => 'صورة الباقة يجب أن تكون صورة صالحة.',
                'packages.*.image_path.max' => 'صورة الباقة يجب ألا تتجاوز 2 ميجابايت.',
                'packages.*.title.required' => 'عنوان الباقة مطلوب.',
                'packages.*.title.string' => 'عنوان الباقة يجب أن يكون نصاً.',
                'packages.*.title.max' => 'عنوان الباقة يجب ألا يتجاوز 255 حرفاً.',
                'packages.*.description.required' => 'وصف الباقة مطلوب.',
                'packages.*.description.string' => 'وصف الباقة يجب أن يكون نصاً.',
                'packages.*.price.required' => 'سعر الباقة مطلوب.',
                'packages.*.price.numeric' => 'سعر الباقة يجب أن يكون رقماً.',
                'packages.*.discounted_price.numeric' => 'السعر المخفض للباقة يجب أن يكون رقماً.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            $offer = new Offer();
            $offer->company_id = $request->user()->company_id;
            $offer->title = $request['title'];
            $offer->category_id = $request['category_id'];
            $offer->description = $request['description'];
            $offer->start_date = $request['start_date'];
            $offer->end_date = $request['end_date'];

            // Handle image uploads
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('offer-images', 'public');
            }
            $offer->images = $images;

            $offer->save();

            // Handle packages
            if (!empty($request['packages'])) {
                foreach ($request['packages'] as $packageData) {
                    $offer->packages()->create([
                        'image_path' => $packageData['image_path']->store('package-images', 'public'),
                        'title' => $packageData['title'],
                        'description' => $packageData['description'],
                        'price' => $packageData['price'],
                        'discounted_price' => $packageData['discounted_price'] ?? null,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Offer created successfully',
                'token' => $offer,
            ], 200);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating offer: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    }
