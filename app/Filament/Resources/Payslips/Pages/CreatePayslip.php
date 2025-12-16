<?php

namespace App\Filament\Resources\Payslips\Pages;

use App\Filament\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Payslip;
use App\Models\User;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CreatePayslip extends CreateRecord
{
    protected static string $resource = PayslipResource::class;

    /* ---------------------------------------------
     |  CUT-OFF CONFIG
     | --------------------------------------------- */
    protected function getCutoffData(): array
    {
        $filePath = base_path('cutoff-bases.json');

        if (!File::exists($filePath)) {
            return [
                'first_cutoff_day' => 15,
                'second_cutoff_day' => 30,
                'sss_rate' => 0,
                'pagibig_rate' => 0,
                'philhealth_rate' => 0,
            ];
        }

        return json_decode(File::get($filePath), true);
    }

    /* ---------------------------------------------
     |  PAY PERIOD DATES
     | --------------------------------------------- */
    protected function calculatePayPeriodDates(string $period): array
    {
        $cutoff = $this->getCutoffData();
        $now = Carbon::now();

        if ($period === 'second') {
            $start = Carbon::create($now->year, $now->month, $cutoff['second_cutoff_day']);
            $end = $start->copy()->addMonth()->day($cutoff['first_cutoff_day'] - 1);
        } else {
            $start = Carbon::create($now->year, $now->month, $cutoff['first_cutoff_day']);
            $end = Carbon::create($now->year, $now->month, $cutoff['second_cutoff_day'] - 1);
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /* ---------------------------------------------
     |  MAIN RECORD CREATION
     | --------------------------------------------- */
    protected function handleRecordCreation(array $data): Payslip
    {
        $payPeriod = $data['pay_period'] ?? 'first';
        $dates = $this->calculatePayPeriodDates($payPeriod);
        $cutoff = $this->getCutoffData();

        // ✅ Get all coaches
        $coaches = User::where('role', 'coach')->get();

        foreach ($coaches as $coach) {

            $basicSalary = $coach->daily_basic_salary ?? 0;

            // 1️⃣ Attendance deduction & days worked
            $attendance = $this->calculateAttendance($coach->id, $basicSalary, Carbon::create($data['period_start']), Carbon::create($data['period_end']));
            $daysWorked = $attendance['days_worked'];
            $attendanceDeduction = $attendance['deduction'];

            // Total basic pay based on days worked minus undertime deduction
            $adjustedBasic = max(0, ($basicSalary * $daysWorked) - $attendanceDeduction);

            // 2️⃣ Gross pay
            $gross = $adjustedBasic + ($data['allowances'] ?? 0) + ($data['overtime_pay'] ?? 0);

            // 3️⃣ Mandatory Deductions
            $sss = $cutoff['sss_rate'] ?? 0;
            $philhealth = $cutoff['philhealth_rate'] ?? 0;
            $pagibig = $cutoff['pagibig_rate'] ?? 0;

            // 4️⃣ Taxable Income
            $taxable = $gross - ($sss + $philhealth + $pagibig);

            // 5️⃣ Tax
            $tax = $this->computePhTax($taxable);

            // 6️⃣ Totals
            $totalDeductions = $attendanceDeduction + $tax + $sss + $philhealth + $pagibig;
            $netPay = $gross - ($tax + $sss + $philhealth + $pagibig);

            Payslip::create([
                'user_id' => $coach->id,
                'period_start' => $dates['start']->format('Y-m-d'),
                'period_end' => $dates['end']->format('Y-m-d'),
                'basic_salary' => $basicSalary,
                'days_worked' => $daysWorked, // optional column in DB
                'total_salary' => round($gross, 2),
                'allowances' => $data['allowances'] ?? 0,
                'overtime_pay' => $data['overtime_pay'] ?? 0,
                'tax' => round($tax, 2),
                'sss' => $sss,
                'philhealth' => $philhealth,
                'pagibig' => $pagibig,
                'total_deductions' => round($totalDeductions, 2),
                'net_pay' => round($netPay, 2),
            ]);
        }

        // Filament requires one model return
        return Payslip::latest()->first();
    }

    /* ---------------------------------------------
     |  PH TAX (TRAIN LAW)
     | --------------------------------------------- */
    protected function computePhTax(float $income): float
    {
        if ($income <= 20833) return 0;
        if ($income <= 33332) return ($income - 20833) * 0.15;
        if ($income <= 66666) return 1875 + (($income - 33333) * 0.20);
        if ($income <= 166666) return 8541.80 + (($income - 66667) * 0.25);
        if ($income <= 666666) return 33541.80 + (($income - 166667) * 0.30);

        return 183541.80 + (($income - 666667) * 0.35);
    }

    /* ---------------------------------------------
     |  ATTENDANCE CALCULATION
     | --------------------------------------------- */
    protected function calculateAttendance(
        int $userId,
        float $dailyRate,
        Carbon $start,
        Carbon $end
    ): array {
        $hourlyRate = $dailyRate / 8;

        $logs = AttendanceLog::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->get();

        $deduction = 0;
        $daysWorked = 0;

        foreach ($logs as $log) {
            if (!$log->time_in || !$log->time_out) {
                // No record = no pay for the day
                $deduction += $dailyRate;
                continue;
            }

            $workedHours = Carbon::parse($log->time_in)
                ->diffInMinutes(Carbon::parse($log->time_out)) / 60;

            $daysWorked++; // count as attended day

            if ($workedHours < 8) {
                $deduction += (8 - $workedHours) * $hourlyRate;
            }
        }

        return [
            'deduction' => round($deduction, 2),
            'days_worked' => $daysWorked,
        ];
    }
}
