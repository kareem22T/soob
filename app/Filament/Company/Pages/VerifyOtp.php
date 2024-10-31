<?php

namespace App\Filament\Company\Pages;

use App\Services\ForJawalyService;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VerifyOtp extends SimplePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.company.pages.verify-otp';

    public $phone;
    public $otp;

    public function mount()
    {
        $this->phone = Auth::guard('company')->user()->phone;
        $this->sendOtp();
    }

    public function sendOtp()
    {
        try {
            $user = Auth::guard('company')->user();

            // Sending SMS via service
            $verificationCode = rand(100000, 999999);

            $expirationTime = Carbon::now()->addMinutes(10);

            $user->update([
                'verification_code' => Hash::make($verificationCode),
                'current_code_expired_at' => $expirationTime,
            ]);

            $result = ForJawalyService::sendSMS($this->phone, "Your Soob account verification code is: {$verificationCode}");

            if ($result['code'] === 200) {
                session()->flash('success', 'We have sent you OTP to your phone: ' . $this->phone);
                Log::info("Sending OTP to {$this->phone} with code {$verificationCode}");
            } else {
                session()->flash('error', 'Your phone not correct or does not exists');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Invalid phone Please try again.');
        }
    }

    public function submitOtp()
    {
        if (!$this->otp)
            session()->flash('error', 'Please enter the OTP');

        $user = Auth::guard('company')->user();

        if (Carbon::now()->greaterThan($user->current_code_expired_at)) {
            session()->flash('error', 'OTP has expired. Please request a new one.');
            return back();
        }

        // Check if the OTP matches the hashed verification code
        if (Hash::check($this->otp, $user->verification_code)) {
            // Mark the phone as verified by setting verified_at timestamp
            $user->is_phone_verified = 1;
            $user->save();

            session()->flash('success', 'Phone number verified successfully.');

            if ($user->is_approved)
                return redirect()->to('/company')->with('success', 'You phone has verified successfully'); // Redirect to the desired route after verification
            else
                return redirect()->route('pending.account')->with('success', 'You phone has verified successfully'); // Redirect to the desired route after verification

        } else {
            session()->flash('error', 'Invalid OTP. Please try again.');
            return back();
        }
    }

    public function editPhone()
    {
        return redirect()->route('edit.company.phone');
    }
    public function goToRegister()
    {
        session()->flush();
        return redirect('/company/register'); // Adjust route name if necessary
    }
    public function getTitle(): string | Htmlable
    {
        return false;
    }
    public function getCachedSubNavigation(): array
    {
        return [];
    }
    public function getSubNavigationPosition(): array
    {
        return [];
    }
    public function getWidgetData(): array
    {
        return [];
    }
    public function getHeader(): array
    {
        return [];
    }
        public function getCachedHeaderActions(): array
    {
        return [];
    }
        public function getBreadcrumbs(): array
    {
        return [];
    }
        public function getVisibleHeaderWidgets(): array
    {
        return [];
    }
        public function getVisibleFooterWidgets(): array
    {
        return [];
    }
        public function getFooter(): array
    {
        return [];
    }

}
