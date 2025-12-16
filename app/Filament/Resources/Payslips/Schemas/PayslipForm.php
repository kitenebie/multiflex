<?php

namespace App\Filament\Resources\Payslips\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class PayslipForm
{
    protected static function getCutoffData(): array
    {
        $filePath = base_path('cutoff-bases.json');
        
        if (!File::exists($filePath)) {
            return [
                'first_cutoff_day' => 15,
                'second_cutoff_day' => 30,
                'second_cutoff_type' => 'same_month',
                'sss_rate' => 0,
                'pagibig_rate' => 0,
                'philhealth_rate' => 0,
                'tax_rate' => 0
            ];
        }

        $jsonData = File::get($filePath);
        $data = json_decode($jsonData, true);
        
        return [
            'first_cutoff_day' => $data['first_cutoff_day'] ?? 15,
            'second_cutoff_day' => $data['second_cutoff_day'] ?? 30,
            'second_cutoff_type' => $data['second_cutoff_type'] ?? 'same_month',
            'sss_rate' => $data['sss_rate'] ?? 0,
            'pagibig_rate' => $data['pagibig_rate'] ?? 0,
            'philhealth_rate' => $data['philhealth_rate'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 0
        ];
    }

    protected static function getPayPeriodDates(string $period = 'first'): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];
        
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $nextMonth = $now->copy()->addMonth();
        
        if ($period === 'first') {
            // First Period: first_cutoff_day to (second_cutoff_day - 1)
            $periodStart = Carbon::create($currentYear, $currentMonth, $firstCutoff);
            $periodEnd = Carbon::create($currentYear, $currentMonth, $secondCutoff - 1);
        } else {
            // Second Period: second_cutoff_day to (first_cutoff_day - 1 of next month)
            $periodStart = Carbon::create($currentYear, $currentMonth, $secondCutoff);
            $periodEnd = Carbon::create($nextMonth->year, $nextMonth->month, $firstCutoff - 1);
        }
        
        return [
            'start' => $periodStart->format('Y-m-d'),
            'end' => $periodEnd->format('Y-m-d')
        ];
    }

    protected static function getPayPeriodOptions(): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];
        
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $nextMonth = $now->copy()->addMonth();
        
        // Create Carbon instances for better date formatting
        $firstPeriodStart = Carbon::create($currentYear, $currentMonth, $firstCutoff);
        $firstPeriodEnd = Carbon::create($currentYear, $currentMonth, $secondCutoff - 1);
        $secondPeriodStart = Carbon::create($currentYear, $currentMonth, $secondCutoff);
        $secondPeriodEnd = Carbon::create($nextMonth->year, $nextMonth->month, $firstCutoff - 1);
        
        $options = [
            'first' => "First Period (" . $firstPeriodStart->format('M j Y') . " - " . $firstPeriodEnd->format('M j Y') . ")",
            'second' => "Second Period (" . $secondPeriodStart->format('M j Y') . " - " . $secondPeriodEnd->format('M j Y') . ")",
        ];
        
        return $options;
    }

    protected static function getDisabledDates(string $field, string $period = 'first'): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];
        
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $daysInCurrentMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;
        $nextMonth = $now->copy()->addMonth();
        $daysInNextMonth = Carbon::create($nextMonth->year, $nextMonth->month)->daysInMonth;
        
        $disabledDates = [];
        
        if ($field === 'period_start') {
            if ($period === 'first') {
                // For first period start: only first_cutoff_day should be enabled
                for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
                    if ($day !== $firstCutoff) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            } else {
                // For second period start: only second_cutoff_day should be enabled
                for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
                    if ($day !== $secondCutoff) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            }
        } elseif ($field === 'period_end') {
            if ($period === 'first') {
                // For first period end: only (second_cutoff_day - 1) should be enabled
                $endDay = $secondCutoff - 1;
                for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
                    if ($day !== $endDay) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            } else {
                // For second period end: only (first_cutoff_day - 1 of next month) should be enabled
                $endDay = $firstCutoff - 1;
                for ($day = 1; $day <= $daysInNextMonth; $day++) {
                    if ($day !== $endDay) {
                        $disabledDates[] = Carbon::create($nextMonth->year, $nextMonth->month, $day)->format('Y-m-d');
                    }
                }
            }
        }
        
        return $disabledDates;
    }

    public static function configure(Schema $schema): Schema
    {
        $cutoffData = self::getCutoffData();
        $payPeriodOptions = self::getPayPeriodOptions();
        $firstPeriod = self::getPayPeriodDates('first');
        $secondPeriod = self::getPayPeriodDates('second');
        
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options([
                        'all' => 'All Coaches'
                    ])
                    ->label('Coach')
                    ->disabled()
                    ->default('all')
                    ->required(),
                Select::make('pay_period')
                    ->label('Pay Period')
                    ->options($payPeriodOptions)
                    ->default('first')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $periodDates = self::getPayPeriodDates($state);
                        
                        $set('period_start', $periodDates['start']);
                        $set('period_end', $periodDates['end']);
                    })
                    ->required(),
                DatePicker::make('period_start')
                    ->label('Period Start')
                    ->default($firstPeriod['start'])
                    ->disabledDates(fn ($get) => $get('pay_period') === 'first' 
                        ? self::getDisabledDates('period_start', 'first')
                        : self::getDisabledDates('period_start', 'second')
                    )
                    ->required(),
                DatePicker::make('period_end')
                    ->label('Period End')
                    ->default($firstPeriod['end'])
                    ->disabledDates(fn ($get) => $get('pay_period') === 'first' 
                        ? self::getDisabledDates('period_end', 'first')
                        : self::getDisabledDates('period_end', 'second')
                    )
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
                    ->default($cutoffData['tax_rate']),
                TextInput::make('sss')
                    ->required()
                    ->numeric()
                    ->default($cutoffData['sss_rate']),
                TextInput::make('philhealth')
                    ->required()
                    ->numeric()
                    ->default($cutoffData['philhealth_rate']),
                TextInput::make('pagibig')
                    ->required()
                    ->numeric()
                    ->default($cutoffData['pagibig_rate']),
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
