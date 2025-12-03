<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'fitness_offers' => 'Fitness Offers',
                            'sales' => 'Sales',
                            'overall_sales' => 'Overall Sales',
                            'attendance' => 'Attendance',
                            'subscription' => 'Subscription',
                            'revenue' => 'Revenue',
                            default => $state,
                        };
                    }),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(function ($state) {
                        return $state ? basename($state) : 'No file generated';
                    }),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
