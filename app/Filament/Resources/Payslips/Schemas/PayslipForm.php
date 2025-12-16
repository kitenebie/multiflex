<?php

namespace App\Filament\Resources\Payslips\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;


class PayslipForm
{
    protected static function getCutoffData(): array
    {
        $filePath = base_path('cutoff-bases.json');
        
        if (!File::exists($filePath)) {
            return [
                'sss_rate' => 0,
                'pagibig_rate' => 0,
                'philhealth_rate' => 0,
                'tax_rate' => 0
            ];
        }

        $jsonData = File::get($filePath);
        $data = json_decode($jsonData, true);
        
        return [
            'sss_rate' => $data['sss_rate'] ?? 0,
            'pagibig_rate' => $data['pagibig_rate'] ?? 0,
            'philhealth_rate' => $data['philhealth_rate'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 0
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        $cutoffData = self::getCutoffData();
        
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
                    ->disabled(),
                TextInput::make('allowances')
                    ->required()
                    ->disabled()
                    ->default(0.0),
                TextInput::make('overtime_pay')
                    ->required()
                    ->disabled()
                    ->default(0.0),
                TextInput::make('sss')
                    ->required()
                    ->disabled()
                    ->default($cutoffData['sss_rate']),
                TextInput::make('philhealth')
                    ->required()
                    ->disabled()
                    ->default($cutoffData['philhealth_rate']),
                TextInput::make('pagibig')
                    ->required()
                    ->disabled()
                    ->default($cutoffData['pagibig_rate']),
                TextInput::make('total_deductions')
                    ->required()
                    ->disabled()
                    ->default(0.0),
                TextInput::make('net_pay')
                    ->required()
                    ->disabled()
                    ->default(0.0),
            ]);
    }
}
