<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class Payslip extends Model
{
    protected $fillable = [
        'employee_id',
        'period_start',
        'period_end',
        'basic_salary',
        'allowances',
        'overtime_pay',
        'tax',
        'sss',
        'philhealth',
        'pagibig',
        'total_deductions',
        'net_pay',
    ];

    protected static function booted()
    {
        static::saving(function ($payslip) {

            // 1️⃣ Attendance deduction
            $attendanceDeduction = self::calculateAttendanceDeduction($payslip);

            // Reduce basic salary
            $adjustedBasicSalary = max(
                0,
                $payslip->basic_salary - $attendanceDeduction
            );

            // 2️⃣ Gross pay
            $gross =
                $adjustedBasicSalary +
                $payslip->allowances +
                $payslip->overtime_pay;

            // 3️⃣ Taxable income
            $taxable = $gross
                - $payslip->sss
                - $payslip->philhealth
                - $payslip->pagibig;

            // 4️⃣ Compute PH tax
            $payslip->tax = self::computePhTax($taxable);

            // 5️⃣ Total deductions
            $payslip->total_deductions =
                $attendanceDeduction +
                $payslip->tax +
                $payslip->sss +
                $payslip->philhealth +
                $payslip->pagibig;

            // 6️⃣ Net pay
            $payslip->net_pay = $gross - (
                $payslip->tax +
                $payslip->sss +
                $payslip->philhealth +
                $payslip->pagibig
            );
        });
    }

    /**
     * PH Monthly Withholding Tax (TRAIN Law)
     */
    protected static function computePhTax(float $income): float
    {
        if ($income <= 20833) {
            return 0;
        } elseif ($income <= 33332) {
            return ($income - 20833) * 0.15;
        } elseif ($income <= 66666) {
            return 1875 + (($income - 33333) * 0.20);
        } elseif ($income <= 166666) {
            return 8541.80 + (($income - 66667) * 0.25);
        } elseif ($income <= 666666) {
            return 33541.80 + (($income - 166667) * 0.30);
        }

        return 183541.80 + (($income - 666667) * 0.35);
    }

    protected static function calculateAttendanceDeduction(self $payslip): float
    {
        $dailyRate = $payslip->basic_salary; // 8-hr salary
        $hourlyRate = $dailyRate / 8;

        $logs = AttendanceLog::where('user_id', $payslip->employee_id)
            ->whereBetween('date', [$payslip->period_start, $payslip->period_end])
            ->get();

        $totalDeduction = 0;

        foreach ($logs as $log) {
            if (!$log->time_in || !$log->time_out) {
                // no time record = full deduction
                $totalDeduction += $dailyRate;
                continue;
            }

            $workedHours = Carbon::parse($log->time_in)
                ->diffInMinutes(Carbon::parse($log->time_out)) / 60;

            if ($workedHours < 8) {
                $missingHours = 8 - $workedHours;
                $totalDeduction += $missingHours * $hourlyRate;
            }
        }

        return round($totalDeduction, 2);
    }
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class)->role('coach');
    }
}
