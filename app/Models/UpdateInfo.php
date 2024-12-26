<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UpdateInfo extends Model
{
    protected $fillable = [
        'system_info_id',
        'count'
    ];

    public function systemInfo(): BelongsTo
    {
        return $this->belongsTo(SystemInfo::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(UpdatePackage::class);
    }
} 