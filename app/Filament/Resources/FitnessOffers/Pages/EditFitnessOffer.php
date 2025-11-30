<?php

namespace App\Filament\Resources\FitnessOffers\Pages;

use App\Filament\Resources\FitnessOffers\FitnessOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFitnessOffer extends EditRecord
{
    protected static string $resource = FitnessOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
