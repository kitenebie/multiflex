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
                TextColumn::make('employee.name')
                    ->label('Coach name')
                    ->searchable(),
                TextColumn::make('period_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('period_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label('Total daily basic salary')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('total_salary')
                    ->label('Total monthly basic salary')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('net_pay')
                    ->label('Gross Pay (Net Pay)')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('total_deductions')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('pagibig')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('philhealth')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('sss')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('tax')
                    ->numeric()
                    ->money('PHP')
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
            ]);
    }
}
