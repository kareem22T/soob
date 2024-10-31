<?php

namespace App\Filament\Company\Resources\UserCustomeRequestsResource\Pages;

use App\Filament\Company\Resources\UserCustomeRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserCustomeRequests extends ListRecords
{
    protected static string $resource = UserCustomeRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
