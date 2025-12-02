<?php

namespace App\Filament\Resources\SubscriptionTransactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubscriptionTransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('subscription.fitnessOffer.name')
                    ->label('Subscription'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('payment_method'),
                TextEntry::make('reference_no'),
                TextEntry::make('paid_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
