<?php

namespace App\Filament\Resources\CoachHadles\Pages;

use App\Filament\Resources\CoachHadles\CoachHadleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoachHadle extends ViewRecord
{
    protected static string $resource = CoachHadleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
