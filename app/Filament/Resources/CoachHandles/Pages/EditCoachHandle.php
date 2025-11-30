<?php

namespace App\Filament\Resources\CoachHandles\Pages;

use App\Filament\Resources\CoachHandles\CoachHandleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCoachHandle extends EditRecord
{
    protected static string $resource = CoachHandleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
