<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\BookingResource\Pages;
use App\Filament\Company\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->disabled()
                    ->label('User')
                    ->options(function () {
                        return \App\Models\User::pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->disabled()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->disabled()
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('note')
                    ->disabled()
                    ->required()
                    ->maxLength(255),
                Select::make('offer_id')
                    ->disabled()
                    ->label('Offer')
                    ->options(function () {
                        return \App\Models\Offer::pluck('title', 'id');
                    })
                    ->required(),
                Select::make('package_id')
                    ->disabled()
                    ->label('Package')
                    ->options(function () {
                        return \App\Models\Package::pluck('title', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('booking_status')
                    ->options(function () {
                        return [
                            'pending' => 'Pending',
                            'cancelled' => 'Cancelled',
                            'confirmed' => 'Confirmed',
                        ];
                    })
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->options(function () {
                        return [
                            'failed' => 'Failed',
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                        ];
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->forCompany())
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('phone')
                ->searchable(),
            Tables\Columns\TextColumn::make('note')
                ->searchable(),
            Tables\Columns\TextColumn::make('offer.title')
                ->sortable(),
            Tables\Columns\TextColumn::make('package.title')
                ->sortable(),

            BadgeColumn::make('booking_status')
                ->label('Booking Status')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'pending',
                    'success' => 'confirmed',
                ])
                ->sortable(),

                BadgeColumn::make('payment_status')
                ->label('Payment Status')
                ->colors([
                    'danger' => 'failed',
                    'warning' => 'pending',
                    'success' => 'paid',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])

        ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
