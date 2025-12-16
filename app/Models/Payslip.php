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

    // ✅ CORRECT relationship (NO ROLE FILTER HERE)
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
