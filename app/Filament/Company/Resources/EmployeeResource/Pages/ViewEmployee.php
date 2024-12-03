<?php

namespace App\Filament\Company\Resources\EmployeeResource\Pages;

use App\Filament\Company\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
