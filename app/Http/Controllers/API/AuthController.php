<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Phone;
use App\Services\ForJawalyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'required|string|max:15|unique:users,phone',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'name.required' => 'اسم المستخدم مطلوب.',
                'name.string' => 'اسم المستخدم يجب أن يكون نصاً.',
                'name.max' => 'اسم المستخدم يجب ألا يزيد عن 255 حرفًا.',

                'email.required' => 'البريد الإلكتروني مطلوب.',
                'email.email' => 'يجب إدخال بريد إلكتروني صحيح.',
                'email.max' => 'البريد الإلكتروني يجب ألا يزيد عن 255 حرفًا.',
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',

                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
                'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفًا.',
                'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

                'password.required' => 'كلمة المرور مطلوبة.',
                'password.string' => 'كلمة المرور يجب أن تكون نصاً.',
                'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
                'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_phone_verified_for_web_registeration' => true, // Set phone verification as false initially
            ]);

            // Generate token
            $token = $user->createToken('UserToken')->plainTextToken;
            $user->is_phone_verified = $user->is_phone_verified_for_web_registeration;

            return response()->json([
                'status' => 'success',
                'message' => 'تم التسجيل بنجاح',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Throwable $th) {
            Log::error("Error in User Registration: " . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => ['حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.'],
            ], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:15',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
                'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفًا.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['رقم الهاتف مستخدم بالفعل.'],
                ], 422);
            }

            $verificationCode = rand(1000, 9999);
            $expirationTime = Carbon::now()->addMinutes(10);

            Phone::updateOrCreate(
                ['phone' => $request->phone],
                [
                    'verification_code' => Hash::make($verificationCode),
                    'verified_at' => null,
                    'current_code_expired_at' => $expirationTime,
                ]
            );

            ForJawalyService::sendSMS($request->phone, "Your verification code is: {$verificationCode}");

            return response()->json([
                'status' => 'success',
                'message' => 'تم إرسال رمز التحقق إلى هاتفك بنجاح.',
            ], 200);
        } catch (\Throwable $th) {
            Log::error("Error in sending OTP: " . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => ['حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.'],
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identifier' => 'required|string',
                'password' => 'required|string',
            ], [
                'identifier.required' => 'البريد الإلكتروني أو رقم الهاتف مطلوب.',
                'identifier.string' => 'يجب أن يكون البريد الإلكتروني أو رقم الهاتف نصاً.',
                'password.required' => 'كلمة المرور مطلوبة.',
                'password.string' => 'كلمة المرور يجب أن تكون نصاً.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            // Determine if identifier is an email or phone
            $identifier = $request->identifier;
            $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
                ? User::where('email', $identifier)->first()
                : User::where('phone', $identifier)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => ['بيانات الاعتماد غير صحيحة.'],
                    ], 401);
                }

            $token = $user->createToken('UserToken')->plainTextToken;
            $user->is_phone_verified = $user->is_phone_verified_for_web_registeration;

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل الدخول بنجاح',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }


    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:15',
                'otp' => 'required|numeric',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب.',
                'otp.required' => 'رمز التحقق مطلوب.',
                'otp.numeric' => 'رمز التحقق يجب أن يكون عدداً.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            $phoneRecord = Phone::where('phone', $request->phone)->first();
            if (!$phoneRecord || !Hash::check($request->otp, $phoneRecord->verification_code)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['رمز التحقق غير صحيح.'],
                ], 422);
            }

            $phoneRecord->update([
                'verified_at' => Carbon::now(),
            ]);

            User::where('phone', $request->phone)->update(['is_phone_verified' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم التحقق من رقم الهاتف بنجاح.',
            ], 200);
        } catch (\Throwable $th) {
            Log::error("Error in verifying OTP: " . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => ['حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.'],
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        $user->is_phone_verified = $user->is_phone_verified_for_web_registeration;

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث رقم الهاتف بنجاح',
            'user' => $user,
        ], 200);
    }

}
