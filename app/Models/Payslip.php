<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            $payslip->total_deductions =
                $payslip->tax +
                $payslip->sss +
                $payslip->philhealth +
                $payslip->pagibig;

            $payslip->net_pay =
                ($payslip->basic_salary +
                $payslip->allowances +
                $payslip->overtime_pay) -
                $payslip->total_deductions;
        });
    }

    public function employee()
    {
        return $this->belongsTo(User::class)->role('coach');
    }
}
