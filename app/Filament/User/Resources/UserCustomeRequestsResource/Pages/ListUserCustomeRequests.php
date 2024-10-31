<?php

namespace App\Filament\User\Resources\UserCustomeRequestsResource\Pages;

use App\Filament\User\Resources\UserCustomeRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListUserCustomeRequests extends ListRecords
{
    protected static string $resource = UserCustomeRequestsResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'My requests';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
