<?php

namespace App\Company;

use App\Models\Company;
use App\Models\Employee;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Pages\Auth\Register as AuthRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Register extends AuthRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        // Parent Section Wrapping Both Sections
                        Section::make('')
                            ->schema([
                                // Company Data Section
                                Section::make('Company Data')
                                    ->schema([
                                        TextInput::make('company_name')
                                            ->label('Company Name')
                                            ->required(),
                                        FileUpload::make('license')
                                            ->label('Company License')
                                            ->required()
                                            ->disk('public')  // Specify the disk
                                            ->directory('company-licenses')  // Optional: specify a directory                                        TextInput::make('address')
                                            ->label('Company License')
                                            ->required(),
                                        TextInput::make('company_email')
                                            ->label('Company Email')
                                            ->email()
                                            ->unique(Company::class, 'email')
                                            ->required(),
                                        TextInput::make('company_phone')
                                            ->label('Company Phone')
                                            ->required()
                                            ->unique(Company::class, 'phone')
                                            ->maxLength(15),
                                    ])
                                    ->columns(1)
                                    ->columnSpan([
                                        'default' => 1,
                                        'lg' => 6, // Half width on large screens
                                    ]),

                                // Account Data Section
                                Section::make('Account Data')
                                    ->schema([
                                        TextInput::make('member_name')
                                            ->label('Your Name')
                                            ->required(),
                                        TextInput::make('member_email')
                                            ->label('Email')
                                            ->email()
                                            ->unique(Employee::class, 'email')
                                            ->required(),
                                        TextInput::make('member_phone')
                                            ->label('Phone Number')
                                            ->required()
                                            ->maxLength(15)
                                            ->prefix('+966')
                                            ->unique(Employee::class, 'phone')
                                            ->placeholder('1234567890'),
                                        $this->getPasswordFormComponent(),
                                        $this->getPasswordConfirmationFormComponent(),
                                    ])
                                    ->columns(1)
                                    ->columnSpan([
                                        'default' => 1,
                                        'lg' => 6, // Half width on large screens
                                    ])
                            ])
                            ->columns([
                                'default' => 2,
                                'lg' => 1, // Two columns on large screens
                            ])
                            ->id('employee_register_form')
                    ])
                ->statePath('data'),
            ),
        ];
    }

    protected function handleRegistration(array $data): Model
    {
        $company = new Company();
        $company->name = $data['company_name'];
        $company->phone = $data['company_phone'];
        $company->email = $data['company_email'];
        $company->license = $data['license'];
        $company->save();

        $employee = Employee::create([
            "company_id" => $company->id,
            "name" => $data['member_name'],
            "phone" => $data['member_phone'],
            "email" => $data['member_email'],
            "member_role" => 'SEO',
            "password" => Hash::make($data['password']),
        ]);

        return $employee;
    }

}
