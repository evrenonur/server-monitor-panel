<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Server extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'ssh_port',
        'username',
        'password',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'api_key'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($server) {
            $server->api_key = Str::random(64);
        });
    }

    public function getApiKeyAttribute($value)
    {
        if (auth()->check()) {
            return $value;
        }
        return null;
    }

    public function systemInfos()
    {
        return $this->hasMany(SystemInfo::class);
    }
} 