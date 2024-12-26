<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceUsage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'server_id',
        'cpu_usage',
        'memory_usage',
        'memory_total',
        'memory_used',
        'memory_free',
        'disk_usages'
    ];

    protected $casts = [
        'cpu_usage' => 'float',
        'memory_usage' => 'float',
        'memory_total' => 'float',
        'memory_used' => 'float',
        'memory_free' => 'float',
        'disk_usages' => 'json',
        'created_at' => 'datetime'
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
} 