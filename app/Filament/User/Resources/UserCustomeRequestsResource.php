<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\UserCustomeRequestsResource\Pages;
use App\Filament\User\Resources\UserCustomeRequestsResource\RelationManagers;
use App\Models\UserCustomeRequests;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserCustomeRequestsResource extends Resource
{
    protected static ?string $model = UserCustomeRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-top-right-on-square';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'My Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('destination')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),
                    Hidden::make('user_id')
                    ->default(Auth::id()) // Set the default value to the authenticated user's ID
                    ->required(),
                    Repeater::make('days')
                    ->relationship('days') // Define the relationship for packages
                    ->schema([
                        TextInput::make('day')->required(),
                        Textarea::make('description')->required(),
                    ])
                    ->label('My Plan')
                    ->columnSpan('full')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->forUser())
            ->columns([
                Tables\Columns\TextColumn::make('destination')
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUserCustomeRequests::route('/'),
            'create' => Pages\CreateUserCustomeRequests::route('/create'),
            'view' => Pages\ViewUserCustomeRequests::route('/{record}'),
            'edit' => Pages\EditUserCustomeRequests::route('/{record}/edit'),
        ];
    }
}
