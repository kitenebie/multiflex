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
        'user_id',
        'period_start',
        'period_end',
        'basic_salary',
        'days_attended',
        'total_salary',
        'allowances',
        'overtime_pay',
        'tax',
        'sss',
        'philhealth',
        'pagibig',
        'total_deductions',
        'net_pay',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'basic_salary' => 'decimal:2',
        'days_attended' => 'integer',
        'total_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'tax' => 'decimal:2',
        'sss' => 'decimal:2',
        'philhealth' => 'decimal:2',
        'pagibig' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    // ✅ CORRECT relationship (NO ROLE FILTER HERE)
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
