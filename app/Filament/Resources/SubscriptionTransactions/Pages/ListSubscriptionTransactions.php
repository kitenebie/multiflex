<?php

namespace App\Filament\Resources\SubscriptionTransactions\Pages;

use App\Filament\Resources\SubscriptionTransactions\SubscriptionTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class ListSubscriptionTransactions extends ListRecords implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;

    protected static string $resource = SubscriptionTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
