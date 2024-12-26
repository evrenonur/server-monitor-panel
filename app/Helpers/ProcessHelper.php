<?php

namespace App\Helpers;

class ProcessHelper
{
    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            'running' => 'Çalışıyor',
            'sleeping' => 'Beklemede',
            'stopped' => 'Durduruldu',
            'zombie' => 'Zombi',
            default => $status
        };
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'running' => 'success',
            'sleeping' => 'secondary',
            'stopped' => 'warning',
            'zombie' => 'danger',
            default => 'info'
        };
    }

    public static function getStatusIcon(string $status): string
    {
        return match ($status) {
            'running' => 'play',
            'sleeping' => 'pause',
            'stopped' => 'stop',
            'zombie' => 'skull',
            default => 'question'
        };
    }

    public static function getAllStatuses(): array
    {
        return [
            'running' => 'Çalışıyor',
            'sleeping' => 'Beklemede',
            'stopped' => 'Durduruldu',
            'zombie' => 'Zombi'
        ];
    }
} 