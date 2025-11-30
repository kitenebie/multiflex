<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class coachHandle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'coach_id',
        'member_id',
        'fitnessOffer_id',
        'start_at',
        'end_at',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function fitnessOffer(): BelongsTo
    {
        return $this->belongsTo(FitnessOffer::class, 'fitnessOffer_id');
    }
}
