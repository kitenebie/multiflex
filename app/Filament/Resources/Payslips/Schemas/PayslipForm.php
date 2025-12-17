<?php

namespace App\Filament\Resources\Payslips\Schemas;

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

    protected static function getPayPeriodDates(string $period = 'first', ?Carbon $selectedMonth = null): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];

        // Use selected month or current month if not provided
        $now = $selectedMonth ?? Carbon::now();
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

    protected static function getPayPeriodOptions(?Carbon $selectedMonth = null): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];

        // Use selected month or current month if not provided
        $now = $selectedMonth ?? Carbon::now();
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

    protected static function getDisabledDates(string $field, string $period = 'first', ?Carbon $selectedMonth = null): array
    {
        $cutoffData = self::getCutoffData();
        $firstCutoff = $cutoffData['first_cutoff_day'];
        $secondCutoff = $cutoffData['second_cutoff_day'];

        // Use selected month or current month if not provided
        $now = $selectedMonth ?? Carbon::now();
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
                DatePicker::make('selected_month')
                    ->label('Select Month for Payslip Generation')
                    ->default(Carbon::now()->startOfMonth())
                    ->format('m/d/Y')
                    ->displayFormat('F Y')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        // Convert selected month to Carbon instance
                        $selectedMonth = Carbon::parse($state)->startOfMonth();

                        // Update pay period options based on selected month
                        $payPeriodOptions = self::getPayPeriodOptions($selectedMonth);
                        $set('pay_period_options', $payPeriodOptions);

                        // Update period dates based on selected month
                        $periodDates = self::getPayPeriodDates('first', $selectedMonth);
                        $set('period_start', $periodDates['start']);
                        $set('period_end', $periodDates['end']);
                    })
                    ->required(),
                Select::make('pay_period')
                    ->label('Pay Period')
                    ->options(function ($get) use ($payPeriodOptions) {
                        // Use updated options if available, otherwise use default
                        return $get('pay_period_options') ?? $payPeriodOptions;
                    })
                    ->default('first')
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, $get) {
                        // Get selected month or use current month
                        $selectedMonth = $get('selected_month')
                            ? Carbon::parse($get('selected_month'))->startOfMonth()
                            : Carbon::now()->startOfMonth();

                        $periodDates = self::getPayPeriodDates($state, $selectedMonth);

                        $set('period_start', $periodDates['start']);
                        $set('period_end', $periodDates['end']);
                    })
                    ->required(),
                Select::make('employee_id')
                    ->options([
                        'all' => 'All Coaches'
                    ])
                    ->label('Coach')
                    ->disabled()
                    ->default('all')
                    ->required(),
                DatePicker::make('period_start')
                    ->label('Period Start')
                    ->default($firstPeriod['start'])
                    ->format('m/d/Y')
                    ->displayFormat('d F Y')
                    ->disabledDates(
                        fn($get) => $get('pay_period') === 'first'
                            ? self::getDisabledDates('period_start', 'first', $get('selected_month') ? Carbon::parse($get('selected_month'))->startOfMonth() : null)
                            : self::getDisabledDates('period_start', 'second', $get('selected_month') ? Carbon::parse($get('selected_month'))->startOfMonth() : null)
                    )->hidden(false)
                    ->required(),
                DatePicker::make('period_end')
                    ->label('Period End')
                    ->format('m/d/Y')
                    ->displayFormat('d F Y')
                    ->default($firstPeriod['end'])
                    ->disabledDates(
                        fn($get) => $get('pay_period') === 'first'
                            ? self::getDisabledDates('period_end', 'first', $get('selected_month') ? Carbon::parse($get('selected_month'))->startOfMonth() : null)
                            : self::getDisabledDates('period_end', 'second', $get('selected_month') ? Carbon::parse($get('selected_month'))->startOfMonth() : null)
                    )->hidden(false)
                    ->required(),
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
            ]);
    }
}
