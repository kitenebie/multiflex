<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'fitness_offer_id',
        'coach_id',
        'status',
        'start_date',
        'end_date',
        'is_extendable',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_extendable' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fitnessOffer(): BelongsTo
    {
        return $this->belongsTo(FitnessOffer::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function subscriptionTransactions(): HasMany
    {
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function coachHandle(): HasOne
    {
        return $this->hasOne(coachHandle::class, 'member_id', 'user_id')->where('fitnessOffer_id', $this->fitnessOffer_id);
    }
}
