<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ResourceUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // Sunucu istatistikleri
        $serverStats = [
            'total' => Server::count(),
            'active' => Server::where('is_active', true)->count(),
            'inactive' => Server::where('is_active', false)->count(),
        ];

        // Son 24 saatteki yüksek kaynak kullanımı olan sunucular
        $highUsageServers = ResourceUsage::with('server')
            ->where('created_at', '>=', now()->subDay())
            ->where(function($query) {
                $query->where('cpu_usage', '>=', 80)
                    ->orWhere('memory_usage', '>=', 80);
            })
            ->select('server_id',
                DB::raw('MAX(cpu_usage) as max_cpu'),
                DB::raw('MAX(memory_usage) as max_memory'),
                DB::raw('COUNT(*) as alert_count')
            )
            ->groupBy('server_id')
            ->having('alert_count', '>=', 3)
            ->get();

        // Son güncellenen sunucular
        $recentUpdates = Server::with(['systemInfos' => function($query) {
            $query->latest()->limit(1);
        }, 'resourceUsages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->whereHas('systemInfos', function($query) {
            $query->where('created_at', '>=', now()->subDay());
        })
        ->latest()
        ->take(5)
        ->get()
        ->map(function($server) {
            $server->last_update = $server->systemInfos->first()->created_at;
            $server->last_resource_update = $server->resourceUsages->first()?->created_at;
            return $server;
        });

        // Ortalama kaynak kullanımları
        $averageUsage = ResourceUsage::where('created_at', '>=', now()->subDay())
            ->select(
                DB::raw('AVG(cpu_usage) as avg_cpu'),
                DB::raw('AVG(memory_usage) as avg_memory'),
                DB::raw('COUNT(DISTINCT server_id) as server_count')
            )
            ->first();

        return view('admin.dashboard', compact(
            'serverStats',
            'highUsageServers',
            'recentUpdates',
            'averageUsage'
        ));
    }
}
