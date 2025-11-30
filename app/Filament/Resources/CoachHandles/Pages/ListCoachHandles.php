<?php

namespace App\Filament\Resources\CoachHadles\Pages;

use App\Filament\Resources\CoachHadles\CoachHadleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCoachHadles extends ListRecords
{
    protected static string $resource = CoachHadleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
