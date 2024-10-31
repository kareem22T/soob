<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\BookingResource\Pages;
use App\Filament\User\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
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
    protected static ?string $navigationLabel = 'My Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                ->default(Auth::id()) // Set the default value to the authenticated user's ID
                ->required(),
                Select::make('offer_id')
                ->label('Offer')
                ->options(Offer::all()->pluck('title', 'id')) // Load all offers
                ->reactive() // Make it reactive to update the package options
                ->required(),

            Select::make('package_id')
                ->label('Package')
                ->options(function (callable $get) {
                    $offerId = $get('offer_id'); // Get selected offer ID
                    return $offerId ? Package::where('offer_id', $offerId)->pluck('title', 'id') : [];
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
                    ->nullable()
                    ->maxLength(255),
                    Hidden::make('booking_status')
                    ->default('pending') // Set the default value to the authenticated user's ID
                    ->required(),

                    Hidden::make('payment_status')
                    ->default('paid') // Set the default value to the authenticated user's ID
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->forUser())
        ->columns([
            Tables\Columns\TextColumn::make('offer.company.name')
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
