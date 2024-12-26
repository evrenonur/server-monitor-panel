<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Process extends Model
{
    protected $fillable = [
        'process_info_id',
        'pid',
        'name',
        'username',
        'cpu_percent',
        'memory_percent',
        'status'
    ];

    protected $casts = [
        'cpu_percent' => 'float',
        'memory_percent' => 'float'
    ];

    public function processInfo(): BelongsTo
    {
        return $this->belongsTo(ProcessInfo::class);
    }
} 