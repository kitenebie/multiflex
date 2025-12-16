<?php

namespace App\Filament\Resources\Payslips\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;


class PayslipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Coach')
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        logger('Selected user ID:', [$state]); // DEBUG
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
                TextInput::make('overtime_hours')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('overtime_rate')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('overtime_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('deductions')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('gross_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
