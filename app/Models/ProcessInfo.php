<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessInfo extends Model
{
    protected $fillable = [
        'system_info_id',
        'total_processes',
        'running',
        'sleeping',
        'stopped',
        'zombie'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }

    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }
} 