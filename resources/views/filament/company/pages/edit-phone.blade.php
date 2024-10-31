<x-filament-panels::page>
    <div class="space-y-6">
        @if (session('success'))
            <div style="text-align: center;background: #88c273;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div style="text-align: center;background: #ff4e4e;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('error') }}</div>
        @endif

        <form wire:submit.prevent="submitEditPhone" class="space-y-4 bg-white rounded-lg shadow-md">
            <div>
                <label for="phone" class="block text-gray-700">Update you phone</label>
                <input type="text" wire:model="phone" id="phone"  class="block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-[#88c273]">
            </div>

            <button type="submit" class="w-full py-2 bg-[#536493] text-white rounded-md hover:bg-[#4b5f7c]" style="background: #4b5f7c">Submit</button>
        </form>

    </div>
</x-filament-panels::page>
