<?php

namespace App\Filament\Resources\SubscriptionTransactions\Tables;

use App\Models\SubscriptionTransaction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubscriptionTransactionsTable
{
    public static function configure(Table $table): Table
    {
        
        $query = SubscriptionTransaction::query();
        if(Auth::user()->roles()->where('name', 'member')->exists())
        {
            $query->whereHas('subscription', function($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('subscription.fitnessOffer.name')
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('reference_no')
                    ->url(fn ($state) => '/app/subscriptions?search=' . $state)
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                ImageColumn::make('proof_of_payment')
                    ->size(70),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('view_proof')
                    ->label('View Proof')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => asset('storage/' . $record->proof_of_payment))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
