<?php

namespace App\Filament\Resources\FitnessOffers\Pages;

use App\Filament\Resources\FitnessOffers\FitnessOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class ListFitnessOffers extends ListRecords implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;

    protected static string $resource = FitnessOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
