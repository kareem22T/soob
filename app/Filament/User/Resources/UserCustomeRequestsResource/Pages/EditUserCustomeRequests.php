<?php

namespace App\Filament\User\Resources\UserCustomeRequestsResource\Pages;

use App\Filament\User\Resources\UserCustomeRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserCustomeRequests extends EditRecord
{
    protected static string $resource = UserCustomeRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
