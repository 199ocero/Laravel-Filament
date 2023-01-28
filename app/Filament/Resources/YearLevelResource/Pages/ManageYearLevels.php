<?php

namespace App\Filament\Resources\YearLevelResource\Pages;

use App\Filament\Resources\YearLevelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageYearLevels extends ManageRecords
{
    protected static string $resource = YearLevelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
