<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSettingResource\Pages;
use App\Filament\Resources\UserSettingResource\RelationManagers;
use App\Models\UserSetting;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSettingResource extends Resource
{
    protected static ?string $model = UserSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_ad_title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('company_ad_thumbnail')
                    ->required(),
                Forms\Components\Textarea::make('company_ad_description')
                    ->required()
                    ->columnSpanFull(),
                    Forms\Components\TextInput::make('company_ad_action_btn')
                    ->required()
                    ->maxLength(255),
                Select::make('company_id')
                    ->label('Company')
                    ->options(function () {
                        return \App\Models\Company::pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Toggle::make('toggle_company_ad_section')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_ad_title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('company_ad_thumbnail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_ad_action_btn')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('toggle_company_ad_section')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUserSettings::route('/'),
            'create' => Pages\CreateUserSetting::route('/create'),
            'view' => Pages\ViewUserSetting::route('/{record}'),
            'edit' => Pages\EditUserSetting::route('/{record}/edit'),
        ];
    }
}
