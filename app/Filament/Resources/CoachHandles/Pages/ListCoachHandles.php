<?php

namespace App\Filament\Resources\CoachHandles\Pages;

use App\Filament\Resources\CoachHandles\CoachHandleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoachHandles extends ListRecords
{
    protected static string $resource = CoachHandleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
