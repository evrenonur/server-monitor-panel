<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ResourceUsage;
use Illuminate\Http\Request;

class ServerUsageController extends Controller
{
    public function show(Server $server)
    {
        // Son 24 saatin verileri
        $usages = ResourceUsage::where('server_id', $server->id)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at')
            ->get();

        // Metrikler
        $metrics = [
            'today' => [
                'cpu' => [
                    'avg' => $usages->avg('cpu_usage'),
                    'max' => $usages->max('cpu_usage'),
                    'max_time' => $usages->sortByDesc('cpu_usage')->first()?->created_at,
                    'min' => $usages->min('cpu_usage'),
                    'min_time' => $usages->sortBy('cpu_usage')->first()?->created_at,
                ],
                'memory' => [
                    'avg' => $usages->avg('memory_usage'),
                    'max' => $usages->max('memory_usage'),
                    'max_time' => $usages->sortByDesc('memory_usage')->first()?->created_at,
                    'min' => $usages->min('memory_usage'),
                    'min_time' => $usages->sortBy('memory_usage')->first()?->created_at,
                ],
                'disk' => [
                    'avg' => $usages->avg(function($usage) {
                        return collect($usage->disk_usages)->avg('usage_percent');
                    }),
                    'max' => $usages->max(function($usage) {
                        return collect($usage->disk_usages)->avg('usage_percent');
                    }),
                    'max_time' => $usages->sortByDesc(function($usage) {
                        return collect($usage->disk_usages)->avg('usage_percent');
                    })->first()?->created_at,
                ]
            ],
            'hourly' => $usages->groupBy(function($item) {
                return $item->created_at->format('H:00');
            })->map(function($group) {
                return [
                    'cpu' => round($group->avg('cpu_usage'), 1),
                    'memory' => round($group->avg('memory_usage'), 1),
                    'disk' => round($group->avg(function($usage) {
                        return collect($usage->disk_usages)->avg('usage_percent');
                    }), 1),
                    'count' => $group->count()
                ];
            })->sortKeys()
        ];

        return view('admin.servers.usage', [
            'server' => $server,
            'usages' => $usages,
            'metrics' => $metrics
        ]);
    }

    public function data(Server $server, Request $request)
    {
        $period = $request->get('period', '24h');
        $interval = $request->get('interval', '5m'); // 5 dakika varsayılan
        
        $query = ResourceUsage::where('server_id', $server->id);
        
        // Zaman aralığı filtresi
        switch ($period) {
            case '1h':
                $query->where('created_at', '>=', now()->subHour());
                break;
            case '6h':
                $query->where('created_at', '>=', now()->subHours(6));
                break;
            case '12h':
                $query->where('created_at', '>=', now()->subHours(12));
                break;
            case '24h':
                $query->where('created_at', '>=', now()->subDay());
                break;
            case '7d':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30d':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
        }
        
        $usages = $query->orderBy('created_at')->get();
        
        // Güncelleme aralığına göre verileri grupla
        if ($interval !== 'all') {
            $minutes = match($interval) {
                '1m' => 1,
                '5m' => 5,
                '15m' => 15,
                '30m' => 30,
                '1h' => 60,
                default => 5
            };

            $groupedUsages = $usages->groupBy(function($item) use ($minutes) {
                return $item->created_at->startOfMinute()->floor($minutes, 'minute');
            })->map(function($group) {
                return [
                    'cpu_usage' => $group->avg('cpu_usage'),
                    'memory_usage' => $group->avg('memory_usage'),
                    'disk_usage' => $group->avg(function($item) {
                        return collect($item->disk_usages)->avg('usage_percent');
                    }),
                    'created_at' => $group->first()->created_at
                ];
            })->values();

            $usages = collect($groupedUsages);
        }

        // Tarih formatını periyoda göre ayarla
        $dateFormat = match($period) {
            '1h', '6h', '12h' => 'H:i:s',
            '24h' => 'H:i',
            '7d' => 'd M H:i',
            '30d' => 'd M',
            default => 'H:i'
        };

        return response()->json([
            'labels' => $usages->pluck('created_at')->map(fn($date) => $date->format($dateFormat)),
            'cpu' => $usages->pluck('cpu_usage'),
            'memory' => $usages->pluck('memory_usage'),
            'disk' => $period === 'all' 
                ? $usages->pluck('disk_usages')->map(fn($disks) => collect($disks)->avg('usage_percent'))
                : $usages->pluck('disk_usage')
        ]);
    }
} 