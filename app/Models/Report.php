<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'filters',
        'file_path',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'filters' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

}
