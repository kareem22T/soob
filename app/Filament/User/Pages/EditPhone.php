<?php

namespace App\Filament\User\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class EditPhone extends SimplePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.edit-phone';
    public $phone;

    public function mount()
    {
        $this->phone = Auth::user()->phone;

        session()->flash('error', '');
    }


    public function submitEditPhone()
    {
        $request = request();

        // Check if the phone field is present
        if (!$this->phone) {
            session()->flash('error', 'The phone field is required.');
            return back(); // Redirect back to the same page
        }

        $is_phone = User::where('phone', $this->phone)->where('id', '!=', Auth::id())->first();

        if ($is_phone) {
            session()->flash('error', 'This phone is allready taken');
            return back(); // Redirect back to the same page
        }

        $user = Auth::user();

        if ($user) {
            $user->update([
                'phone' => $this->phone,
            ]);

            session()->flash('success', 'Phone changed successfully.');
            return redirect()->route('verify.user.phone');
        }
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
