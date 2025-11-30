<?php

namespace App\Filament\Resources\FitnessOffers\Pages;

use App\Filament\Resources\FitnessOffers\FitnessOfferResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFitnessOffer extends ViewRecord
{
    protected static string $resource = FitnessOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
