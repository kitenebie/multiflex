<?php

namespace App\Filament\Resources\Payslips\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Employee Information
                TextColumn::make('employee.name')
                    ->label('Employee Name')
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->description(fn($record) => $record->employee->email ?? ''),
                
                // Pay Period
                TextColumn::make('period')
                    ->label('Pay Period')
                    ->getStateUsing(fn($record) => $record->period_start->format('M j') . ' - ' . $record->period_end->format('M j, Y'))
                    ->searchable(),
                
                // Attendance
                TextColumn::make('days_attended')
                    ->label('Days Attended')
                    ->numeric()
                    ->sortable()
                    ->suffix(' days'),
                
                // Earnings Section
                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('total_salary')
                    ->label('Monthly Basic')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('allowances')
                    ->label('Allowances')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('overtime_pay')
                    ->label('Overtime')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                
                // Deductions Section
                TextColumn::make('sss')
                    ->label('SSS')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('philhealth')
                    ->label('PhilHealth')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('pagibig')
                    ->label('PAG-IBIG')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('tax')
                    ->label('Tax')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                
                TextColumn::make('total_deductions')
                    ->label('Total Deductions')
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),
                
                // Net Pay
                TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->numeric()
                    ->money('PHP')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('success'),
                
                // Actions and Timestamps
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters for date range and employee if needed
            ])
            ->defaultSort('created_at', 'desc');
    }
}
