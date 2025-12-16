<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Payslip;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CreatePayslip extends CreateRecord
{
    protected static string $resource = PayslipResource::class;

    protected function getCutoffData(): array
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

    protected function calculatePayPeriodDates(string $period = 'first'): array
    {
        $cutoffData = $this->getCutoffData();
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

    protected function handleRecordCreation(array $data): Payslip
    {
        // Get cutoff data and calculate dates based on pay_period
        $payPeriod = $data['pay_period'] ?? 'first';
        $periodDates = $this->calculatePayPeriodDates($payPeriod);
        $cutoffData = $this->getCutoffData();

        // 🔥 ALL COACHES SELECTED
        $coaches = User::role('coach')->get();

        foreach ($coaches as $coach) {
            Payslip::create([
                'employee_id'  => $coach->id,
                'period_start' => $periodDates['start'],
                'period_end'   => $periodDates['end'],
                'basic_salary' => $coach->daily_basic_salary ?? 0, // using daily_basic_salary field
                'allowances'   => $data['allowances'] ?? 0,
                'overtime_pay' => $data['overtime_pay'] ?? 0,
                'tax'          => $data['tax'] ?? $cutoffData['tax_rate'],
                'sss'          => $data['sss'] ?? $cutoffData['sss_rate'],
                'philhealth'   => $data['philhealth'] ?? $cutoffData['philhealth_rate'],
                'pagibig'      => $data['pagibig'] ?? $cutoffData['pagibig_rate'],
            ]);
        }

        // Filament requires one model returned
        return Payslip::latest()->first();
    }
}
