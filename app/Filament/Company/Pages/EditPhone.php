<?php

namespace App\Filament\Company\Pages;

use App\Models\Company;
use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class EditPhone extends SimplePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.company.pages.edit-phone';
    public $phone;

    public function mount()
    {
        $this->phone = Auth::guard('employee')->user()->phone;

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

        $is_phone = Company::where('phone', $this->phone)->where('id', '!=', Auth::id())->first();

        if ($is_phone) {
            session()->flash('error', 'This phone is allready taken');
            return back(); // Redirect back to the same page
        }

        $user = Auth::guard('employee')->user();

        if ($user) {
            $user->update([
                'phone' => $this->phone,
            ]);

            session()->flash('success', 'Phone changed successfully.');
            return redirect()->route('verify-company-phone');
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
