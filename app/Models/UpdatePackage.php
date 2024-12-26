<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdatePackage extends Model
{
    protected $fillable = [
        'update_info_id',
        'package',
        'current_version',
        'new_version',
        'architecture',
        'distribution'
    ];

    public function updateInfo(): BelongsTo
    {
        return $this->belongsTo(UpdateInfo::class);
    }
} 