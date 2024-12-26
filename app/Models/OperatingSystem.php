<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatingSystem extends Model
{
    protected $fillable = [
        'system_info_id',
        'name',
        'version',
        'os_id',
        'version_id'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }
} 