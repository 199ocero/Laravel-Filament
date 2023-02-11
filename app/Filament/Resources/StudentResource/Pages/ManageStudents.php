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
        ];
    }
}
