<?php

namespace App\Filament\Resources\CoachHandles\Tables;

use App\Models\FitnessOffer;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoachHandlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('coach.name')
                    ->label('Coach')
                    ->searchable(),
                TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('fitnessOffer.name')
                    ->label('Fitness Offer')
                    ->searchable(),
                TextColumn::make('start_at')
                    ->label('Start At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->label('End At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('coach_id')
                    ->label('Coach')
                    ->options(User::where('role', 'coach')->pluck('name', 'id')),
                SelectFilter::make('member_id')
                    ->label('Member')
                    ->options(User::where('role', 'member')->pluck('name', 'id')),
                SelectFilter::make('fitnessOffer_id')
                    ->label('Fitness Offer')
                    ->options(FitnessOffer::pluck('name', 'id')),
                TrashedFilter::make(),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
                // DeleteAction::make()->label('Archive')->icon('heroicon-o-archive-box-x-mark'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
