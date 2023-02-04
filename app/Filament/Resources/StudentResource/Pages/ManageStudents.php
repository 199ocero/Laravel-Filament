<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Models\User;
use Filament\Pages\Actions;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\ManageRecords;

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
