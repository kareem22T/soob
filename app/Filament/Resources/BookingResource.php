<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
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

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(function () {
                        return \App\Models\User::pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('note')
                    ->required()
                    ->maxLength(255),
                Select::make('offer_id')
                    ->label('Offer')
                    ->options(function () {
                        return \App\Models\Offer::pluck('title', 'id');
                    })
                    ->required(),
                Select::make('package_id')
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
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('phone')
                ->searchable(),
            Tables\Columns\TextColumn::make('note')
                ->searchable(),
            Tables\Columns\TextColumn::make('offer.title')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('offer.company.name')
                ->label('Company name')
                ->sortable(),
                Tables\Columns\TextColumn::make('offer.company.phone')
                ->label('Company title')
                ->sortable(),
            Tables\Columns\TextColumn::make('package.title')
                ->numeric()
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
