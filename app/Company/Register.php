<?php

namespace App\Company;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister
{
    protected function getForms(): array
    {

        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        TextInput::make('phone')
                        ->label('Phone Number')
                        ->required()
                        ->maxLength(15)
                        ->prefix('+966')
                        ->placeholder('1234567890'),
                        $this->getEmailFormComponent(),
                        FileUpload::make('license')
                        ->label('Company License')
                        ->required(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

}
