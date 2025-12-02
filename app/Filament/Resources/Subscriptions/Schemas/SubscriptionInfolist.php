<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Models\Subscription;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('fitnessOffer.name')
                    ->label('Fitness offer'),
                TextEntry::make('coach.name')
                    ->label('Coach')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('subscriptionTransactions.amount')
                    ->money('PHP'),
                TextEntry::make('subscriptionTransactions.payment_method'),
                TextEntry::make('subscriptionTransactions.reference_no'),
                TextEntry::make('subscriptionTransactions.paid_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Subscription $record): bool => $record->trashed()),
            ]);
    }
}
