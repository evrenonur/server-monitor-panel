<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkInterface extends Model
{
    protected $fillable = [
        'system_info_id',
        'name',
        'ip_address',
        'netmask',
        'mac_address'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }
} 