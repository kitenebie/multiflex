<?php

namespace App\Filament\Resources\Payslips\Tables;

use App\Models\Payslip;
use App\Models\User;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;

class PayslipsTable
{
    public static function configure(Table $table): Table
    {
        $user = Auth::user();

        $query = $user && $user->role === 'coach'
            ? Payslip::query()->where('user_id', $user->id)->where('is_submit', true)
            : Payslip::query();
        return $table->query($query->orderByDesc('id'))
            ->columns([
                TextColumn::make('is_submit')
                    ->label('Status')
                    ->badge()
                    ->toggleable()
                    ->getStateUsing(fn($record) => $record->is_submit ? 'Submitted' : 'Draft')
                    ->color(fn($record) => $record->is_submit ? 'success' : 'warning')
                    ->sortable(),
                // Employee Information
                TextColumn::make('employee.name')
                    ->label('Employee Name')
                    ->searchable()
                    ->toggleable()
                    ->weight(FontWeight::Bold)
                    ->description(fn($record) => $record->employee?->email ?? ''),

                // Pay Period
                TextColumn::make('period')
                    ->label('Pay Period')
                    ->getStateUsing(function ($record) {
                        return ($record->period_start?->format('M j') ?? 'N/A') . ' - ' . ($record->period_end?->format('M j, Y') ?? 'N/A');
                    })
                    ->toggleable()
                    ->searchable(),

                // Attendance
                TextColumn::make('days_attended')
                    ->label('Days Attended')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->suffix(' days'),

                // Earnings Section
                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('total_salary')
                    ->label('Monthly Basic')
                    ->numeric()
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->toggleable()
                    ->money('PHP')
                    ->sortable(),
                // Net Pay
                TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->numeric()
                    ->toggleable()
                    ->money('PHP')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('success'),

                // Deductions Section
                TextColumn::make('sss')
                    ->label('SSS')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('philhealth')
                    ->label('PhilHealth')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('pagibig')
                    ->label('PAG-IBIG')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('tax')
                    ->label('Tax')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('total_deductions')
                    ->label('Total Deductions')
                    ->color('warning')
                    ->weight(FontWeight::Bold)
                    ->numeric()
                    ->money('PHP')
                    ->sortable(),

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
            ])->columnManagerColumns(3)
            ->filters([
                // Add filters for date range and employee if needed
            ])->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => '/payslips/view/' . $record->id),
                EditAction::make()
                    ->schema([
                        Grid::make()->schema([
                            Select::make('user_id')
                                ->label('Employee')
                                ->relationship('employee', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            DatePicker::make('period_start')
                                ->label('Period Start')
                                ->required(),

                            DatePicker::make('period_end')
                                ->label('Period End')
                                ->required(),

                            TextInput::make('basic_salary')
                                ->label('Basic Salary')
                                ->numeric()
                                ->prefix('₱')
                                ->required(),

                            TextInput::make('days_attended')
                                ->label('Days Attended')
                                ->numeric()
                                ->required(),

                            TextInput::make('total_salary')
                                ->label('Total Salary')
                                ->numeric()
                                ->prefix('₱')
                                ->required(),

                            TextInput::make('allowances')
                                ->label('Allowances')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('overtime_pay')
                                ->label('Overtime Pay')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('sss')
                                ->label('SSS')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('philhealth')
                                ->label('PhilHealth')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('pagibig')
                                ->label('PAG-IBIG')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('tax')
                                ->label('Tax')
                                ->numeric()
                                ->prefix('₱'),

                            TextInput::make('total_deductions')
                                ->label('Total Deductions')
                                ->numeric()
                                ->prefix('₱')
                                ->required(),

                            TextInput::make('net_pay')
                                ->label('Net Pay')
                                ->numeric()
                                ->prefix('₱')
                                ->required(),

                            Toggle::make('is_submit')
                                ->label('Submit Status'),
                        ])->columns(2)->columnSpanFull(),
                    ]),
            ])
            ->toolbarActions([
            ])
            ->defaultSort('created_at', 'desc');
    }
}
