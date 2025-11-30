<?php

namespace App\Filament\Resources\FitnessOffers\Pages;

use App\Filament\Resources\FitnessOffers\FitnessOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFitnessOffers extends ListRecords
{
    protected static string $resource = FitnessOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
