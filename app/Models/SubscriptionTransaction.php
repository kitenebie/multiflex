<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'amount',
        'payment_method',
        'reference_no',
        'paid_at',
        'proof_of_payment',
        'active_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'active_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
