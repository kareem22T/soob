<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\ForJawalyService;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected $forJawalyService;

    public function __construct(ForJawalyService $forJawalyService)
    {
        $this->forJawalyService = $forJawalyService;
    }

    // Send OTP to the user's phone
    public function sendOtp(Request $request)
    {
        // Validate the phone number
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $phone = $request->phone;
        $verificationCode = rand(1000, 9999);
        $expirationTime = Carbon::now()->addMinutes(10);

        // Check if the phone exists, then update or create a new phone record
        $phoneRecord = Phone::updateOrCreate(
            ['phone' => $phone],
            [
                'verification_code' => Hash::make($verificationCode),
                'verified_at' => null,
                'current_code_expired_at' => $expirationTime,
            ]
        );

        Log::info("Sending OTP to {$phone} with code {$verificationCode}");

        try {
            $result = $this->forJawalyService->sendSMS($phone, "Your account verification code is: {$verificationCode}");

            if ($result['code'] === 200) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'phone' => $phone,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Error occurred while sending OTP',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }
    }

    // Verify the OTP sent to the user's phone
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'phone' => 'required',
        ]);

        $phoneRecord = Phone::where('phone', $request->phone)->first();

        if (!$phoneRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number not found.',
            ], 404);
        }

        if (Carbon::now()->greaterThan($phoneRecord->current_code_expired_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }

        if (Hash::check($request->otp, $phoneRecord->verification_code)) {
            $phoneRecord->verified_at = now();
            $phoneRecord->save();

            return response()->json([
                'success' => true,
                'message' => 'Phone number verified successfully.',
                'phone' => $phoneRecord->phone,
                'isVerified' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }
    }
}
