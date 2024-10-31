<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Visitor;
use App\Models\Resident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function userRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required||unique:users',
            'password' => 'required|confirmed|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_phone_verified_for_web_registeration' => true,
                'password' => Hash::make($request->password),
            ]);

            $user->save();

            return response()->json(['status' => 'success', 'message' => 'تم التسجيل بنجاح']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'حدث خطأ ما'], 500);
        }
    }


    // Login API
    public function userLogin(Request $request)
    {
        // Validation request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('access_token')->plainTextToken;
                return response()->json(
                    [
                        'success' => true,
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'data' => $user
                    ],
                    200
                );
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // Logout API
    public function userLogout(Request $request)
    {

        $request->user()->currentAccessToken()->delete(); // Delete access token
        return response()->json(['success' => true, 'message' => 'User logged out successfully.'], 200);
    }
}
