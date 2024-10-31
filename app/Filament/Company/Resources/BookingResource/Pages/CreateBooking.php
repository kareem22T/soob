<?php

namespace App\Filament\Company\Resources\BookingResource\Pages;

use App\Filament\Company\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
}
