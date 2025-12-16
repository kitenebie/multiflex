<?php

namespace App\Filament\Resources\Payslips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('period_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtime_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtime_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtime_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deductions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gross_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
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
