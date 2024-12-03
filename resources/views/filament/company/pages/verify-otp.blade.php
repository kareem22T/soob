<x-filament-panels::page>
    <div class="space-y-6">
        @if (session('success'))
            <div style="text-align: center;background: #88c273;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div style="text-align: center;background: #ff4e4e;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="submitOtp" class="space-y-4 bg-white rounded-lg shadow-md">
            {{-- <h2 class="text-xl font-semibold text-gray-800 text-center">We have sent you OTP to your phone <strong style="font-size: 27px;margin-top: 13px;display: block;">{{ Auth::guard('employee')->user()->phone }}</strong></h2> --}}
            <button wire:click="editPhone" style="background: #353535;padding: 8px 16px;border-radius: 10px;color: #fff;font-weight: 700;margin: 32px auto 24px;display: block;">Edit Phone</button>
            <div>
                <label for="otp" class="block text-gray-700">Enter OTP</label>
                <input type="text" wire:model="otp" id="otp"  class="block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#88c273]">
            </div>

            <button type="submit" class="w-full py-2 bg-[#536493] text-white rounded-md hover:bg-[#4b5f7c]" style="background: #4b5f7c">Submit</button>
            <button wire:click="sendOtp" type="button" class="w-full py-2 text-white rounded-md hover:bg-[#76b661]" style="background: #88c273">Resend OTP</button>
        </form>

        <button wire:click="goToRegister" class="text-[] hover:underline" style="background: transparent; border: none;color: #536493;display: flex;align-items: center;gap: 8px;margin-top: 26px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2"> <path d="M5 12l14 0"></path> <path d="M5 12l6 6"></path> <path d="M5 12l6 -6"></path> </svg> Back to Register</button>
    </div>
</x-filament-panels::page>
