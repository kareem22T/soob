<?php

namespace App\Filament\User\Resources\UserCustomeRequestsResource\Pages;

use App\Filament\User\Resources\UserCustomeRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserCustomeRequests extends ViewRecord
{
    protected static string $resource = UserCustomeRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
