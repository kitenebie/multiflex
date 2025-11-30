<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FitnessOffer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'upgrade_to',
    ];

    protected $casts = [ 
        'description' => 'array',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function upgradeTo(): BelongsTo
    {
        return $this->belongsTo(FitnessOffer::class, 'upgrade_to');
    }

    public function upgrades(): HasMany
    {
        return $this->hasMany(FitnessOffer::class, 'upgrade_to');
    }
}
