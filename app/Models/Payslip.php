<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'basic_salary',
        'overtime_hours',
        'overtime_rate',
        'overtime_amount',
        'deductions',
        'gross_amount',
        'net_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'basic_salary' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the payslip.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
