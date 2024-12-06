<?php

namespace App\Company;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Pages\Auth\Register as AuthRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
        $company = Company::create([
            'name' => $data['company_name'],
            'phone' => $data['company_phone'],
            'email' => $data['company_email'],
            'license' => $data['license'],
        ]);

        $employee = Employee::create([
            'company_id' => $company->id,
            'name' => $data['member_name'],
            'phone' => $data['member_phone'],
            'email' => $data['member_email'],
            'member_role' => 'Module Specific',
            'password' => Hash::make($data['password']),
        ]);

        // Define resources
        $resources = ['booking', 'employee', 'offer', 'role', 'user_custom_request'];

        // Permissions to generate for each resource
        $permissionActions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
        ];

        // Create roles and permissions for each resource
        foreach ($resources as $resource) {
            // Create a role for the resource
            $roleName = ucfirst($resource) . ' Manager';
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'employee']);

            // Create permissions for the resource
            foreach ($permissionActions as $action) {
                $permissionName = "{$action}_{$resource}";
                $permission = Permission::updateOrCreate(['name' => $permissionName, 'guard_name' => 'employee']);

                // Assign permissions to the role
                $role->givePermissionTo($permission);
            }

            // Assign the role to the employee
            $employee->assignRole($role);
        }

        return $employee;
    }
}
