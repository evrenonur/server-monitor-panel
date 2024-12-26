<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiskInfo extends Model
{
    protected $fillable = [
        'system_info_id',
        'device',
        'mountpoint',
        'total_gb',
        'used_gb',
        'free_gb',
        'usage_percent'
    ];

    protected $casts = [
        'total_gb' => 'float',
        'used_gb' => 'float',
        'free_gb' => 'float',
        'usage_percent' => 'float'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }
} 