<?php

namespace App\Filament\Resources\SchoolYearResource\Pages;

use App\Models\Campus;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\SchoolYearResource;

class ManageSchoolYears extends ManageRecords
{
    protected static string $resource = SchoolYearResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $year = intval($data['name']);
                    $data['name'] = $data['name'] . "-" . ($year + 1);
                    return $data;
                }),
        ];
    }
}
