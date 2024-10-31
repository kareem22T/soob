<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;

class PendingAccount extends SimplePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.user.pages.pending-account';

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
