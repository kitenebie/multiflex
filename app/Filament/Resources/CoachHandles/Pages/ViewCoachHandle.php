<?php

namespace App\Filament\Resources\CoachHandles\Pages;

use App\Filament\Resources\CoachHandles\CoachHandleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoachHandle extends ViewRecord
{
    protected static string $resource = CoachHandleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
