<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\OfferResource\Pages;
use App\Filament\Company\Resources\OfferResource\RelationManagers;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Hidden::make('company_id')
            ->default(Auth::id()) // Set the default value to the authenticated user's ID
            ->required(),
            TextInput::make('title')->required(),
            Select::make('category_id')
            ->placeholder('Select category')
            ->label('Category')
            ->options(function () {
                return \App\Models\Category::pluck('name', 'id');
            })
            ->required(),
            Textarea::make('description')->required(),
            DateTimePicker::make('start_date')->required(),
            DateTimePicker::make('end_date')->required(),
            FileUpload::make('images')
                ->multiple() // Enable multiple file uploads
                ->required()
                ->disk('public')
                ->label('Offer Images')
                ->columnSpan('full'),
            Repeater::make('packages')
                ->relationship('packages') // Define the relationship for packages
                ->schema([
                    FileUpload::make('image_path')
                        ->directory('package-images')
                        ->disk('public')
                        ->label('Package Image')
                        ->required(),
                    TextInput::make('title')->required(),
                    Textarea::make('description')->required(),
                    TextInput::make('price')
                        ->numeric()
                        ->required(),
                    TextInput::make('discounted_price')
                        ->numeric()
                        ->label('Discounted Price')
                        ->nullable(),
                ])
                ->label('Offer Packages')
                ->columnSpan('full')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->forCompany())
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                ->getStateUsing(function ($record) {
                    return $record->images[0];
                })
                ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'view' => Pages\ViewOffer::route('/{record}'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
