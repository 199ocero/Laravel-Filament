<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Models\User;
use App\Models\Student;
use Filament\Pages\Actions;
use App\Services\ImportService;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\ManageRecords;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                    if (!Role::where('name', 'student')->exists()) {
                        Role::create(['name' => 'student']);
                    }

                    $user = new User();
                    $user->name = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
                    $user->email = $data['email'];
                    $user->password = Hash::make($data['birthday']);
                    $user->save();

                    $user->assignRole('student');

                    $data['user_id'] = $user->id;

                    return $data;
                }),
            ImportAction::make()
                ->handleBlankRows(true)
                ->uniqueField('email')
                ->uniqueField('lrn')
                ->fields([
                    ImportField::make('lrn')
                        ->label('LRN')
                        ->required(),
                    ImportField::make('email')
                        ->label('Email')
                        ->required(),
                    ImportField::make('first_name')
                        ->label('First Name')
                        ->required(),
                    ImportField::make('middle_name')
                        ->label('Middle Name')
                        ->required(),
                    ImportField::make('last_name')
                        ->label('Last Name')
                        ->required(),
                    ImportField::make('suffix')
                        ->label('Suffix'),
                    ImportField::make('birthday')
                        ->label('Birthday')
                        ->required(),
                ], columns: 3)
                ->handleRecordCreation(function ($data) {
                    if (!Role::where('name', 'student')->exists()) {
                        Role::create(['name' => 'student']);
                    }

                    $user = new User();
                    $user->name = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];
                    $user->email = $data['email'];
                    $user->password = Hash::make($data['birthday']);
                    $user->save();

                    $user->assignRole('student');

                    $data['user_id'] = $user->id;

                    return Student::create($data);
                })
        ];
    }
}
