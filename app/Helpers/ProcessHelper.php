<?php

namespace App\Helpers;

class ProcessHelper
{
    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            'running' => 'Çalışıyor',
            'sleeping' => 'Uyuyor',
            'stopped' => 'Durmuş',
            'zombie' => 'Zombi',
            default => $status
        };
    }
} 