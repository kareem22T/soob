<?php

namespace App\Filament\User\Resources\OfferResource\Pages;

use App\Filament\User\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOffer extends ViewRecord
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
