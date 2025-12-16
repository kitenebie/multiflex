<?php

namespace App\Filament\Resources\Payslips\Tables;

use App\Models\Payslip;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Ymsoft\FilamentTablePresets\Filament\Actions\ManageTablePresetAction;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        $query = Auth::user()->role === 'coach'
            ? Payslip::where('user_id', Auth::id())
            : Payslip::query();
        return $table->query($query)
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
                    ->getStateUsing(function ($record) {
                        return $record->period_start->format('M j') . ' - ' . $record->period_end->format('M j, Y');
                    })
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

                // // Status
                // TextColumn::make('is_submit')
                //     ->label('Status')
                //     ->badge()
                //     ->getStateUsing(fn($record) => $record->is_submit ? 'Submitted' : 'Draft')
                //     ->color(fn($record) => $record->is_submit ? 'success' : 'warning')
                //     ->sortable(),

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
            ])->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => '/payslips/view/'.$record->id),
            ])
            ->toolbarActions([
                ManageTablePresetAction::make()->label(' ')
                    ->button()
                    ->tooltip('manage table'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
