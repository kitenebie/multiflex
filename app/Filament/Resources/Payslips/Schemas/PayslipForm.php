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
        $secondCutoffType = $cutoffData['second_cutoff_type'];

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        if ($period === 'first') {
            // First period: Day 1 to first cutoff day of current month
            $periodStart = Carbon::create($currentYear, $currentMonth, 1);
            $periodEnd = Carbon::create($currentYear, $currentMonth, $firstCutoff);
        } else {
            // Second period logic
            if ($secondCutoffType === 'same_month') {
                // Both cutoffs in same month: (first cutoff + 1) to second cutoff
                $periodStart = Carbon::create($currentYear, $currentMonth, $firstCutoff + 1);
                $periodEnd = Carbon::create($currentYear, $currentMonth, $secondCutoff);
            } else {
                // Second cutoff in next month: (first cutoff + 1) to end of current month
                $periodStart = Carbon::create($currentYear, $currentMonth, $firstCutoff + 1);
                $periodEnd = Carbon::create($currentYear, $currentMonth)->endOfMonth();
            }
        }

        return [
            'start' => $periodStart->format('Y-m-d'),
            'end' => $periodEnd->format('Y-m-d')
        ];
    }

    protected static function getDisabledDates(string $field, string $period = 'first'): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];
        $secondCutoffType = $cutoffData['second_cutoff_type'];

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $daysInMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;

        $disabledDates = [];

        if ($field === 'period_start') {
            if ($period === 'first') {
                // For first period start: only day 1 should be enabled
                for ($day = 2; $day <= $daysInMonth; $day++) {
                    $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                }
            } else {
                // For second period start: only (first cutoff + 1) should be enabled
                $startDay = $firstCutoff + 1;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    if ($day !== $startDay) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            }
        } elseif ($field === 'period_end') {
            if ($period === 'first') {
                // For first period end: only first cutoff day should be enabled
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    if ($day !== $firstCutoff) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            } else {
                // For second period end: depends on cutoff type
                if ($secondCutoffType === 'same_month') {
                    // Only second cutoff day should be enabled
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        if ($day !== $secondCutoff) {
                            $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                        }
                    }
                } else {
                    // Only end of month should be enabled
                    for ($day = 1; $day < $daysInMonth; $day++) {
                        $disabledDates[] = Carbon::create($currentYear, $currentMonth, $day)->format('Y-m-d');
                    }
                }
            }
        }

        return $disabledDates;
    }

    public static function configure(Schema $schema): Schema
    {
        $cutoffData = self::getCutoffData();
        $firstPeriod = self::getPayPeriodDates('first');
        $secondPeriod = self::getPayPeriodDates('second');

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
                Select::make('pay_period')
                    ->label('Pay Period')
                    ->options([
                        'first' => 'First Period (' . $firstPeriod['start'] . ' to ' . $firstPeriod['end'] . ')',
                        'second' => 'Second Period (' . $secondPeriod['start'] . ' to ' . $secondPeriod['end'] . ')',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) use ($firstPeriod, $secondPeriod) {
                        if ($state === 'first') {
                            $set('period_start', $firstPeriod['start']);
                            $set('period_end', $firstPeriod['end']);
                        } else {
                            $set('period_start', $secondPeriod['start']);
                            $set('period_end', $secondPeriod['end']);
                        }
                    })
                    ->required(),
                DatePicker::make('period_start')
                    ->label('Period Start')
                    ->default($firstPeriod['start'])
                    ->disabledDates(self::getDisabledDates('period_start', 'first'))
                    ->disabled()
                    ->required(),
                DatePicker::make('period_end')
                    ->label('Period End')
                    ->default($firstPeriod['end'])
                    ->disabledDates(self::getDisabledDates('period_end', 'first'))
                    ->disabled()
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
