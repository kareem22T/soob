<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Services\ForJawalyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class CompanyRegistrationController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'company_phone' => 'required|string|max:15|unique:companies,phone',
                'company_email' => 'required|email|max:255|unique:companies,email',
                'license' => 'required|file|mimes:jpg,png,pdf|max:2048',
                'member_name' => 'required|string|max:255',
                'member_email' => 'required|email|max:255|unique:employees,email',
                'member_phone' => 'required|string|max:15|unique:employees,phone',
                'password' => 'required|string|min:8|confirmed',
            ], [
                'company_name.required' => 'اسم الشركة مطلوب.',
                'company_name.string' => 'اسم الشركة يجب أن يكون نصاً.',
                'company_name.max' => 'اسم الشركة يجب ألا يزيد عن 255 حرفًا.',

                'company_phone.required' => 'رقم هاتف الشركة مطلوب.',
                'company_phone.string' => 'رقم هاتف الشركة يجب أن يكون نصاً.',
                'company_phone.max' => 'رقم هاتف الشركة يجب ألا يزيد عن 15 حرفًا.',
                'company_phone.unique' => 'رقم هاتف الشركة مستخدم بالفعل.',

                'company_email.required' => 'البريد الإلكتروني للشركة مطلوب.',
                'company_email.email' => 'يجب إدخال بريد إلكتروني صحيح.',
                'company_email.max' => 'البريد الإلكتروني يجب ألا يزيد عن 255 حرفًا.',
                'company_email.unique' => 'البريد الإلكتروني للشركة مستخدم بالفعل.',

                'license.required' => 'الترخيص مطلوب.',
                'license.file' => 'يجب أن يكون الترخيص ملفًا.',
                'license.mimes' => 'يجب أن يكون الترخيص بصيغة JPG أو PNG أو PDF.',
                'license.max' => 'حجم ملف الترخيص يجب ألا يزيد عن 2 ميغابايت.',

                'member_name.required' => 'اسم العضو مطلوب.',
                'member_name.string' => 'اسم العضو يجب أن يكون نصاً.',
                'member_name.max' => 'اسم العضو يجب ألا يزيد عن 255 حرفًا.',

                'member_email.required' => 'البريد الإلكتروني للعضو مطلوب.',
                'member_email.email' => 'يجب إدخال بريد إلكتروني صحيح.',
                'member_email.max' => 'البريد الإلكتروني يجب ألا يزيد عن 255 حرفًا.',
                'member_email.unique' => 'البريد الإلكتروني للعضو مستخدم بالفعل.',

                'member_phone.required' => 'رقم هاتف العضو مطلوب.',
                'member_phone.string' => 'رقم هاتف العضو يجب أن يكون نصاً.',
                'member_phone.max' => 'رقم هاتف العضو يجب ألا يزيد عن 15 حرفًا.',
                'member_phone.unique' => 'رقم هاتف العضو مستخدم بالفعل.',

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

            // Store company license
            $licensePath = $request->file('license')->store('company-licenses', 'public');

            // Create company
            $company = Company::create([
                'name' => $request->company_name,
                'phone' => $request->company_phone,
                'email' => $request->company_email,
                'license' => $licensePath,
            ]);

            // Create employee
            $employee = Employee::create([
                'company_id' => $company->id,
                'name' => $request->member_name,
                'phone' => $request->member_phone,
                'email' => $request->member_email,
                'member_role' => 'SEO',
                'password' => Hash::make($request->password),
            ]);

                    // Define resources
        $resources = ['booking', 'employee', 'offer', 'role', 'user_custom_request'];

        // Permissions to generate for each resource
        $permissionActions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];

        // Create roles and permissions for each resource
        foreach ($resources as $resource) {
            // Create a role for the resource
            $roleName = ucfirst($resource) . ' Manager';
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'employee']);

            // Create permissions for the resource
            foreach ($permissionActions as $action) {
                $permissionName = "{$action}_{$resource}";
                $permission = Permission::updateOrCreate(['name' => $permissionName, 'guard_name' => 'employee']);

                // Assign permissions to the role
                $role->givePermissionTo($permission);
            }

            // Assign the role to the employee
            $employee->assignRole($role);
        }

            $employee->is_approved = $company->is_approved;

            // Generate token
            $token = $employee->createToken('EmployeeToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'company' => $company,
                'employee' => $employee,
                'token' => $token,
            ], 201);
        } catch (\Throwable $th) {
            Log::info($th);
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
            $employee = filter_var($identifier, FILTER_VALIDATE_EMAIL)
                ? Employee::where('email', $identifier)->first()
                : Employee::where('phone', $identifier)->first();

            if (!$employee || !Hash::check($request->password, $employee->password)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['بيانات الاعتماد غير صحيحة.'],
                ], 401);
            }

            $token = $employee->createToken('EmployeeToken')->plainTextToken;

            $company = Company::find($employee->company_id);
            $employee->is_approved = $company->is_approved;

            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل الدخول بنجاح',
                'token' => $token,
                'employee' => $employee,
            ], 200);
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }

    public function updatePhone(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:15|unique:employees,phone',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
                'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفًا.',
                'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            $employee = $request->user();
            $token = $employee->createToken('EmployeeToken')->plainTextToken;
            $company = Company::find($employee->company_id);
            $employee->is_approved = $company->is_approved;

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['المستخدم غير موجود.'],
                ], 404);
            }

            $employee->update([
                'phone' => $request->phone,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث رقم الهاتف بنجاح',
                'token' => $token,
                'employee' => $employee,
            ], 200);
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }
    public function sendOtp(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:15|exists:employees,phone',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
                'phone.max' => 'رقم الهاتف يجب ألا يزيد عن 15 حرفًا.',
                'phone.exists' => 'رقم الهاتف غير موجود في السجلات.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            // Fetch the user associated with the phone
            $employee = Employee::where('phone', $request->phone)->first();

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['المستخدم غير موجود.'],
                ], 404);
            }

            // Generate OTP and expiration
            $verificationCode = rand(100000, 999999);
            $expirationTime = Carbon::now()->addMinutes(10);

            // Update user with OTP and expiration
            $employee->update([
                'verification_code' => Hash::make($verificationCode),
                'current_code_expired_at' => $expirationTime,
            ]);

            // Send OTP via SMS
            $result = ForJawalyService::sendSMS($request->phone, "Your Soob account verification code is: {$verificationCode}");

            if ($result['code'] === 200) {
                Log::info("OTP sent to {$request->phone}: {$verificationCode}");
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم إرسال رمز التحقق إلى هاتفك بنجاح.',
                ], 200);
            }

            // Handle SMS sending failure
            return response()->json([
                'status' => 'error',
                'errors' => ['فشل في إرسال رمز التحقق. يرجى المحاولة لاحقًا.'],
            ], 500);

        } catch (\Exception $e) {
            Log::error("Error in sendOtp: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => ['حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.'],
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:15|exists:employees,phone',
                'otp' => 'required',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب.',
                'phone.exists' => 'رقم الهاتف غير موجود.',
                'otp.required' => 'رمز التحقق مطلوب.',
                'otp.numeric' => 'رمز التحقق يجب أن يكون عدداً.',
                'otp.digits' => 'رمز التحقق يجب أن يتكون من 6 أرقام.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [$validator->errors()->first()],
                ], 422);
            }

            $employee = Employee::where('phone', $request->phone)->first();

            if (!$employee || !Hash::check($request->otp, $employee->verification_code)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['رمز التحقق غير صحيح أو منتهي الصلاحية.'],
                ], 401);
            }

            // Check expiration
            if (Carbon::now()->greaterThan($employee->current_code_expired_at)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['رمز التحقق منتهي الصلاحية.'],
                ], 401);
            }

            // OTP verified successfully
            $employee->update([
                'is_phone_verified' => 1,
                'verification_code' => null,
                'current_code_expired_at' => null,
            ]);

            $token = $employee->createToken('EmployeeToken')->plainTextToken;
            $company = Company::find($employee->company_id);
            $employee->is_approved = $company->is_approved;

            return response()->json([
                'status' => 'success',
                'message' => 'تم التحقق بنجاح.',
                'token' => $token,
                'employee' => $employee,
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error in verifyOtp: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'errors' => ['حدث خطأ غير متوقع. يرجى المحاولة لاحقًا.'],
            ], 500);
        }
    }

    public function getUser(Request $request) {
        $employee = $request->user();
        $company = Company::find($employee->company_id);
        $employee->is_approved = $company->is_approved;

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث رقم الهاتف بنجاح',
            'employee' => $employee,
        ], 200);
    }
}
