<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\UserCustomeRequestsResource\Pages;
use App\Filament\Company\Resources\UserCustomeRequestsResource\RelationManagers;
use App\Models\UserCustomeRequests;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserCustomeRequestsResource extends Resource
{
    protected static ?string $model = UserCustomeRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-top-right-on-square';

    protected static ?string $navigationLabel = 'Users Requests';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->label('User')
                ->disabled()
                ->options(function () {
                    return \App\Models\User::pluck('name', 'id');
                })
                ->required(),
                Forms\Components\TextInput::make('destination')
                ->required()
                ->disabled()
                ->maxLength(255),
                Forms\Components\Textarea::make('description')
                ->disabled()
                ->required()
                ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('start_date')
                ->disabled()
                ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                ->disabled()
                ->required(),
            Repeater::make('days')
            ->relationship('days') // Define the relationship for packages
            ->schema([

                Forms\Components\TextInput::make('day')

                ->required(),
                Forms\Components\TextInput::make('description')

                ->required()

                ->label('Description'),
    ])
            ->label('Request Plan')
            ->columnSpan('full')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->sortable(),
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
            'index' => Pages\ListUserCustomeRequests::route('/'),
            'create' => Pages\CreateUserCustomeRequests::route('/create'),
            'view' => Pages\ViewUserCustomeRequests::route('/{record}'),
            'edit' => Pages\EditUserCustomeRequests::route('/{record}/edit'),
        ];
    }
}
