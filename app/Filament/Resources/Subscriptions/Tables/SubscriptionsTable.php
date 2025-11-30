<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Models\CoachHandle;
use App\Models\Subscription;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        $query = Subscription::query();
        if(Auth::user()->roles()->where('name', 'member')->exists())
        {
           $query->where('user_id', Auth::user()->id);
        }
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('fitnessOffer.name')
                    ->searchable(),
                TextColumn::make('subscriptionTransactions.reference_no')
                    ->url(fn ($state) => '/app/subscription-transactions?search=' . $state)
                    ->color('primary')
                    ->searchable(),
                SelectColumn::make('coach_id')
                    ->label('Assigned Coach')
                    ->options(User::where('role', 'coach')->pluck('name', 'id'))
                    ->searchable()
                    ->disabled(fn ($record) => ($record->status === 'active' || $record->status === 'rejected' || $record->status === 'expired' || $record->status === 'inactive'))
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state) {
                            $record->update([
                                'status' => 'active'
                            ]);
                            CoachHandle::create([
                                'coach_id' => $state,
                                'member_id' => $record->user_id,
                                'fitnessOffer_id' => $record->fitness_offer_id,
                                'start_at' => $record->start_date,
                                'end_at' => $record->end_date,
                            ]);
                        }
                    }),
                TextColumn::make('status')
                   ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'expired' => 'danger',
                        'rejected' => 'danger',
                        'inactive' => 'gray',
                    }),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                // IconColumn::make('is_extendable')
                //     ->boolean(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
