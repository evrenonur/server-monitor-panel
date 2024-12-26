<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'ssh_port',
        'username',
        'password',
        'api_key',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ssh_port' => 'integer'
    ];

    public function systemInfos(): HasMany
    {
        return $this->hasMany(SystemInfo::class);
    }

    public function resourceUsages(): HasMany
    {
        return $this->hasMany(ResourceUsage::class);
    }

    // API key oluşturma
    public static function generateApiKey(): string
    {
        return bin2hex(random_bytes(32));
    }
} 