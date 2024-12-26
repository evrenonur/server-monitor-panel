<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSystemInfoRequest;
use App\Models\SystemInfo;
use App\Models\Server;
use App\Models\OperatingSystem;
use App\Models\NetworkInterface;
use App\Models\CpuInfo;
use App\Models\MemoryInfo;
use App\Models\DiskInfo;
use App\Models\UpdateInfo;
use App\Models\UpdatePackage;
use App\Models\ProcessInfo;
use App\Models\Process;
use App\Models\ResourceUsage;
use Illuminate\Support\Facades\Log;

class SystemInfoController extends Controller
{
    public function store(StoreSystemInfoRequest $request)
    {
        try {
            // API key kontrolü
            $apiKey = str_replace('Bearer ', '', $request->header('Authorization'));
            $server = Server::where('api_key', $apiKey)->first();

            if (!$server) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $validated = $request->validated();

            // Ana sistem bilgilerini güncelle veya oluştur
            $systemInfo = SystemInfo::updateOrCreate(
                [
                    'server_id' => $server->id,
                    'hostname' => $validated['system']['hostname']
                ],
                [
                    'architecture' => $validated['system']['architecture'],
                    'processor' => $validated['system']['processor'],
                    'python_version' => $validated['system']['python_version'],
                    'system_timestamp' => $validated['timestamp']
                ]
            );

            // İşletim sistemi bilgilerini güncelle veya oluştur
            OperatingSystem::updateOrCreate(
                ['system_info_id' => $systemInfo->id],
                [
                    'name' => $validated['system']['os']['name'],
                    'version' => $validated['system']['os']['version'],
                    'os_id' => $validated['system']['os']['id'],
                    'version_id' => $validated['system']['os']['version_id']
                ]
            );

            // Network arayüzlerini güncelle
            $systemInfo->networkInterfaces()->delete();
            $networkInterfaces = collect($validated['network']['interfaces'])->map(function ($interface, $name) use ($systemInfo) {
                return [
                    'system_info_id' => $systemInfo->id,
                    'name' => $name,
                    'ip_address' => $interface['ip'],
                    'netmask' => $interface['netmask'],
                    'mac_address' => $interface['mac']
                ];
            })->values()->all();
            NetworkInterface::insert($networkInterfaces);

            // CPU bilgilerini güncelle veya oluştur
            CpuInfo::updateOrCreate(
                ['system_info_id' => $systemInfo->id],
                [
                    'cores' => $validated['resources']['cpu']['cores'],
                    'usage_percent' => $validated['resources']['cpu']['usage_percent']
                ]
            );

            // Memory bilgilerini güncelle veya oluştur
            MemoryInfo::updateOrCreate(
                ['system_info_id' => $systemInfo->id],
                [
                    'total_gb' => $validated['resources']['memory']['total_gb'],
                    'used_gb' => $validated['resources']['memory']['used_gb'],
                    'free_gb' => $validated['resources']['memory']['free_gb'],
                    'usage_percent' => $validated['resources']['memory']['usage_percent']
                ]
            );

            // Disk bilgilerini güncelle
            $systemInfo->diskInfos()->delete();
            $diskInfos = collect($validated['resources']['disks'])->map(function ($disk) use ($systemInfo) {
                return [
                    'system_info_id' => $systemInfo->id,
                    'device' => $disk['device'],
                    'mountpoint' => $disk['mountpoint'],
                    'total_gb' => $disk['total_gb'],
                    'used_gb' => $disk['used_gb'],
                    'free_gb' => $disk['free_gb'],
                    'usage_percent' => $disk['usage_percent']
                ];
            })->all();
            DiskInfo::insert($diskInfos);

            // Güncelleme bilgilerini işle
            if (isset($validated['updates'])) {
                $updateInfo = UpdateInfo::updateOrCreate(
                    ['system_info_id' => $systemInfo->id],
                    ['count' => $validated['updates']['count']]
                );

                $updateInfo->packages()->delete();

                if (!empty($validated['updates']['packages'])) {
                    $updatePackages = collect($validated['updates']['packages'])->map(function ($package) use ($updateInfo) {
                        return [
                            'update_info_id' => $updateInfo->id,
                            'package' => $package['package'],
                            'current_version' => $package['current_version'],
                            'new_version' => $package['new_version'],
                            'architecture' => $package['architecture'],
                            'distribution' => $package['distribution']
                        ];
                    })->all();
                    UpdatePackage::insert($updatePackages);
                }
            }

            // Process bilgilerini kaydet
            if (isset($validated['processes'])) {
                $processInfo = ProcessInfo::updateOrCreate(
                    ['system_info_id' => $systemInfo->id],
                    [
                        'total_processes' => $validated['processes']['total_processes'],
                        'running' => $validated['processes']['stats']['running'],
                        'sleeping' => $validated['processes']['stats']['sleeping'],
                        'stopped' => $validated['processes']['stats']['stopped'],
                        'zombie' => $validated['processes']['stats']['zombie']
                    ]
                );

                // Mevcut process'leri sil
                $processInfo->processes()->delete();

                // Yeni process'leri ekle
                $processes = collect($validated['processes']['processes'])->map(function ($process) use ($processInfo) {
                    return [
                        'process_info_id' => $processInfo->id,
                        'pid' => $process['pid'],
                        'name' => $process['name'],
                        'username' => $process['username'],
                        'cpu_percent' => $process['cpu_percent'],
                        'memory_percent' => $process['memory_percent'],
                        'status' => $process['status'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->all();

                Process::insert($processes);
            }

            // Kaynak kullanımını kaydet
            ResourceUsage::create([
                'server_id' => $server->id,
                'cpu_usage' => $validated['resources']['cpu']['usage_percent'],
                'memory_usage' => $validated['resources']['memory']['usage_percent'],
                'memory_total' => $validated['resources']['memory']['total_gb'],
                'memory_used' => $validated['resources']['memory']['used_gb'],
                'memory_free' => $validated['resources']['memory']['free_gb'],
                'disk_usages' => $validated['resources']['disks']
            ]);

            return response()->json([
                'message' => 'System information updated successfully',
                'data' => $systemInfo->load([
                    'operatingSystem',
                    'networkInterfaces',
                    'cpuInfo',
                    'memoryInfo',
                    'diskInfos',
                    'updateInfo.packages',
                    'processInfo.processes'
                ])
            ], 200);

        } catch (\Exception $e) {
            Log::error('System info save error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while saving system information'
            ], 500);
        }
    }
} 