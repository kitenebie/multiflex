<?php

namespace App\Filament\Resources\Payslips\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;


class PayslipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Coach')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        logger('Selected employee ID:', [$state]); // DEBUG
                        $salary = User::whereKey($state)->value('daily_basic_salary');
                        $set('basic_salary', $salary ?? 0);
                    })
                    ->required(),
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),
                TextInput::make('allowances')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('overtime_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('sss')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('philhealth')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('pagibig')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_deductions')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('net_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
