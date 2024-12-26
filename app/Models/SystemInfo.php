<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemInfo extends Model
{
    protected $fillable = [
        'server_id',
        'hostname',
        'architecture',
        'processor',
        'python_version',
        'system_timestamp'
    ];

    protected $casts = [
        'system_timestamp' => 'datetime'
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function operatingSystem(): HasOne
    {
        return $this->hasOne(OperatingSystem::class);
    }

    public function networkInterfaces(): HasMany
    {
        return $this->hasMany(NetworkInterface::class);
    }

    public function cpuInfo(): HasOne
    {
        return $this->hasOne(CpuInfo::class);
    }

    public function memoryInfo(): HasOne
    {
        return $this->hasOne(MemoryInfo::class);
    }

    public function diskInfos(): HasMany
    {
        return $this->hasMany(DiskInfo::class);
    }

    public function updateInfo(): HasOne
    {
        return $this->hasOne(UpdateInfo::class);
    }

    public function processInfo(): HasOne
    {
        return $this->hasOne(ProcessInfo::class);
    }
} 