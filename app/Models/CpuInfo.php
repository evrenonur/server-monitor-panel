<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpuInfo extends Model
{
    protected $fillable = [
        'system_info_id',
        'cores',
        'usage_percent'
    ];

    protected $casts = [
        'usage_percent' => 'float'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }
} 